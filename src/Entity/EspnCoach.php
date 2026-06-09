<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCoachRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnCoach as EspnCoachDto;

#[ORM\Entity(repositoryClass: EspnCoachRepository::class)]
#[ORM\Table(name: 'easb_espn_coach')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_coach_season', columns: ['espn_id', 'season_id'])]
class EspnCoach
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    private ?string $lastName = null;

    #[ORM\Embedded(class: EspnAddress::class, columnPrefix: 'birth_place_')]
    private EspnAddress $birthPlace;

    #[ORM\Column(nullable: true)]
    private ?int $experience = null;

    #[ORM\Column(nullable: true)]
    private ?string $collegeReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $personReference = null;

    #[ORM\ManyToOne(targetEntity: EspnTeam::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnTeam $team = null;

    #[ORM\ManyToOne(targetEntity: EspnSeason::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeason $season = null;

    public function __construct()
    {
        $this->birthPlace = new EspnAddress();
    }

    public static function buildFindByCriteriaFromDto(EspnCoachDto $dto, EspnSeason $season): array
    {
        return [
            'espnId' => $dto->getId(),
            'season' => $season,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEspnId(): ?string
    {
        return $this->espnId;
    }

    public function setEspnId(?string $espnId): static
    {
        $this->espnId = $espnId;
        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): static
    {
        $this->uid = $uid;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getBirthPlace(): EspnAddress
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(EspnAddress $birthPlace): static
    {
        $this->birthPlace = $birthPlace;
        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(?int $experience): static
    {
        $this->experience = $experience;
        return $this;
    }

    public function getCollegeReference(): ?string
    {
        return $this->collegeReference;
    }

    public function setCollegeReference(?string $v): static
    {
        $this->collegeReference = $v;
        return $this;
    }

    public function getPersonReference(): ?string
    {
        return $this->personReference;
    }

    public function setPersonReference(?string $v): static
    {
        $this->personReference = $v;
        return $this;
    }

    public function getTeam(): ?EspnTeam
    {
        return $this->team;
    }

    public function setTeam(?EspnTeam $team): static
    {
        $this->team = $team;
        return $this;
    }

    public function getSeason(): ?EspnSeason
    {
        return $this->season;
    }

    public function setSeason(?EspnSeason $season): static
    {
        $this->season = $season;
        return $this;
    }
}
