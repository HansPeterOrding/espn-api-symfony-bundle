<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitor as EspnCompetitorDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitor;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitor as EspnCompetitorEntity;

/**
 * @extends ServiceEntityRepository<EspnCompetitorEntity>
 */
class EspnCompetitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnCompetitorEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnCompetition $competition, EspnCompetitorDto $espnCompetitorDto): EspnCompetitorEntity
    {
        $espnCompetitor = new EspnCompetitorEntity();
        if(!$competition->getId()) {
            return $espnCompetitor;
        }

        if (null !== ($existingEntity = $this->findOneBy(
                $espnCompetitor->buildFindByCriteriaFromDto($competition, $espnCompetitorDto)
            ))) {
            $espnCompetitor = $existingEntity;
        }

        return $espnCompetitor;
    }

    /**
     * Liefert für eine Season/Week ein Array:
     * [
     *   'NYG' => DateTimeImmutable (Europe/Berlin),
     *   'BUF' => DateTimeImmutable (Europe/Berlin),
     *   ...
     * ]
     */
    public function getKickoffTimesByTeamAbbreviation(int $season, int $week, string $timezone = 'Europe/Berlin'): array
    {
        $qb = $this->createQueryBuilder('cmp')
            ->select('team.abbreviation AS teamAbbr, comp.date AS kickoff')
            ->join('cmp.team', 'team')
            ->join('cmp.competition', 'comp')
            ->join('comp.scheduleEvent', 'evt')
            ->where('evt.seasonId = :season')
            ->andWhere('evt.weekNumber = :week')
            ->andWhere('evt.timeValid = true')
            ->setParameter('season', $season)
            ->setParameter('week', $week);

        /** @var array<int, array{teamAbbr: string, kickoff: \DateTimeInterface}> $rows */
        $rows = $qb->getQuery()->getResult();

        $result = [];
        $tz = new \DateTimeZone($timezone);

        foreach ($rows as $row) {
            $abbr = $row['teamAbbr'];
            $kickoff = $row['kickoff'];

            if (!$kickoff instanceof \DateTimeInterface) {
                continue;
            }

            // Nach Europa/Berlin konvertieren
            if ($kickoff instanceof \DateTimeImmutable) {
                $kickoffLocalized = $kickoff->setTimezone($tz);
            } else {
                /** @var \DateTime $clone */
                $clone = clone $kickoff;
                $kickoffLocalized = \DateTimeImmutable::createFromMutable(
                    $clone->setTimezone($tzBerlin)
                );
            }

            $result[$abbr] = $kickoffLocalized;
        }

        return $result;
    }

    /**
     * Liefert den Kickoff eines einzelnen Teams in deutscher Zeit
     * oder null, wenn kein Spiel für diese Season/Week gefunden wurde.
     */
    public function getKickoffTimeForTeam(string $teamAbbreviation, int $season, int $week, string $timezone = 'Europe/Berlin'): ?\DateTimeImmutable
    {
        $qb = $this->createQueryBuilder('cmp')
            ->select('comp.date AS kickoff')
            ->join('cmp.team', 'team')
            ->join('cmp.competition', 'comp')
            ->join('comp.scheduleEvent', 'evt')
            ->where('evt.seasonId = :season')
            ->andWhere('evt.weekNumber = :week')
            ->andWhere('evt.timeValid = true')
            ->andWhere('team.abbreviation = :abbr')
            ->setParameter('season', $season)
            ->setParameter('week', $week)
            ->setParameter('abbr', $teamAbbreviation)
            ->setMaxResults(1);

        /** @var array{kickoff: \DateTimeInterface}|null $row */
        $row = $qb->getQuery()->getOneOrNullResult();

        if (!$row) {
            return null;
        }

        $kickoff = $row['kickoff'];
        if (!$kickoff instanceof \DateTimeInterface) {
            return null;
        }

        $tz = new \DateTimeZone($timezone);

        if ($kickoff instanceof \DateTimeImmutable) {
            return $kickoff->setTimezone($tz);
        }

        /** @var \DateTime $clone */
        $clone = clone $kickoff;

        return \DateTimeImmutable::createFromMutable(
            $clone->setTimezone($tz)
        );
    }

    /**
     * Liefert für eine Season/Week ein Array der Form:
     *
     * [
     *   'NYG' => [
     *     'kickoff'               => DateTimeImmutable (Europe/Berlin),
     *     'attendance'            => int|null,
     *     'status_clock'          => float,
     *     'status_display_clock'  => string,
     *     'status_period'         => int,
     *     'status_type_type'      => string,
     *     'status_type_detail'    => string,
     *     'opponent'              => 'BUF',
     *     'score_for'             => float|null,
     *     'score_against'         => float|null,
     *     'result'                => 'W'|'L'|'T'|null,
     *     'is_home'               => bool,
     *     'down'                  => int|null,
     *   ],
     *   ...
     * ]
     */
    public function getWeekGameInfoPerTeamAbbreviation(string $season, int $week, string $timezone = 'Europe/Berlin'): array
    {
        $qb = $this->createQueryBuilder('cmp')
            ->select(
                'teamSelf.abbreviation AS teamAbbr',
                'teamOpp.abbreviation AS opponentAbbr',
                'comp.date            AS kickoff',
                'comp.attendance      AS attendance',
                'comp.status.clock     AS statusClock',
                'comp.status.displayClock AS statusDisplayClock',
                'comp.status.period    AS statusPeriod',
                'comp.status.type.type  AS statusTypeType',
                'comp.status.type.detail AS statusTypeDetail',
                'sch.byeWeek AS byeWeek',
                'cmp.score.value       AS teamScore',
                'cmpOpp.score.value    AS oppScore',
                'cmp.winner           AS isWinner',
                'cmp.homeAway         AS homeAway',
                'comp.notes           AS notes'
            )
            ->join('cmp.competition', 'comp')
            ->join('comp.scheduleEvent', 'evt')
            ->join('evt.schedule', 'sch')
            ->join('evt.season', 'ssn')
            ->join('cmp.team', 'teamSelf')
            ->join(EspnCompetitor::class, 'cmpOpp', 'WITH', 'cmpOpp.competition = comp AND cmpOpp != cmp')
            ->join('cmpOpp.team', 'teamOpp')
            ->where('ssn.year = :season')
            ->andWhere('evt.week.number = :week')
            ->andWhere('evt.timeValid = true')
            ->setParameter('season', $season)
            ->setParameter('week', $week);

        /** @var array<int, array<string,mixed>> $rows */
        $rows = $qb->getQuery()->getResult();

        $tz = new \DateTimeZone($timezone);
        $result   = [];

        foreach ($rows as $row) {
            $teamAbbr     = (string) $row['teamAbbr'];
            $opponentAbbr = (string) $row['opponentAbbr'];

            $kickoff = $row['kickoff'] ?? null;
            $attendance = $row['attendance'] ?? null;

            $statusClock         = $row['statusClock'] ?? 0.0;
            $statusDisplayClock  = $row['statusDisplayClock'] ?? '';
            $statusPeriod        = $row['statusPeriod'] ?? 0;
            $statusTypeType      = $row['statusTypeType'] ?? '';
            $statusTypeDetail    = $row['statusTypeDetail'] ?? '';

            $teamScore = $row['teamScore'] ?? null;
            $oppScore  = $row['oppScore'] ?? null;

            $isWinner = $row['isWinner'];
            $homeAway = $row['homeAway'] ?? null;

            $notes = $row['notes'] ?? null;

            // Kickoff in deutsche Zeit wandeln
            $kickoffLocalized = null;
            if ($kickoff instanceof \DateTimeImmutable) {
                $kickoffLocalized = $kickoff->setTimezone($tz);
            } elseif ($kickoff instanceof \DateTime) {
                $clone = clone $kickoff;
                $kickoffLocalized = \DateTimeImmutable::createFromMutable(
                    $clone->setTimezone($tz)
                );
            }

            // Ist das Spiel "final"?
            $statusTypeTypeLower   = strtolower((string) $statusTypeType->value);
            $statusTypeDetailLower = strtolower((string) $statusTypeDetail);

            $isFinal =
                str_contains($statusTypeTypeLower, 'final') ||
                str_contains($statusTypeDetailLower, 'final');

            // Ergebnis W/L/T bestimmen (nur wenn final & Scores vorhanden)
            $resultCode = null;
            if ($isFinal && $teamScore !== null && $oppScore !== null) {
                if ($teamScore > $oppScore) {
                    $resultCode = 'W';
                } elseif ($teamScore < $oppScore) {
                    $resultCode = 'L';
                } else {
                    $resultCode = 'T';
                }
            }

            // Heimteam?
            $isHome = (string) $homeAway->value === 'home';

            // Down ermitteln – HIER musst du ggf. die JSON-Struktur deiner notes anpassen.
            $down = null;
            if (is_array($notes)) {
                // Beispiel: falls deine JSON-Struktur so etwas wie
                // notes["situation"]["lastDownDistance"]["down"] enthält:
                if (isset($notes['situation']['lastDownDistance']['down'])) {
                    $down = (int) $notes['situation']['lastDownDistance']['down'];
                } elseif (isset($notes['situation']['down'])) {
                    $down = (int) $notes['situation']['down'];
                }
            }

            $byeWeek = $row['byeWeek'] ?? null;

            $result[$teamAbbr] = [
                'kickoff'              => $kickoffLocalized,
                'attendance'           => $attendance !== null ? (int) $attendance : null,
                'status_clock'         => (float) $statusClock,
                'status_display_clock' => (string) $statusDisplayClock,
                'status_period'        => (int) $statusPeriod,
                'status_type_type'     => (string) $statusTypeType->value,
                'status_type_detail'   => (string) $statusTypeDetail,
                'opponent'             => $opponentAbbr,
                'score_for'            => $teamScore !== null ? (float) $teamScore : null,
                'score_against'        => $oppScore !== null ? (float) $oppScore : null,
                'result'               => $resultCode,     // 'W', 'L', 'T' oder null
                'is_home'              => $isHome,
                'down'                 => $down,           // int|null, abhängig von notes-Struktur
                'bye_week' => $byeWeek,
            ];
        }

        return $result;
    }

    /**
     * Convenience-Methode: Infos nur für ein Team holen.
     */
    public function getWeekGameInfoForTeam(string $teamAbbr, int $season, int $week): ?array
    {
        $all = $this->getWeekGameInfoByTeamAbbreviation($season, $week);

        return $all[$teamAbbr] ?? null;
    }
}
