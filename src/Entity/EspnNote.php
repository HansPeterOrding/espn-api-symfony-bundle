<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\NoteParentTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnNoteRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnNote as EspnNoteDto;

#[ORM\Entity(repositoryClass: EspnNoteRepository::class)]
#[ORM\Table(name: 'easb_espn_note')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['parent_type'], name: 'idx_espn_note_parent_type')]
class EspnNote
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true, enumType: NoteParentTypeEnum::class)]
    private ?NoteParentTypeEnum $parentType = null;

    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?string $date = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $headline = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $text = null;

    #[ORM\Column(nullable: true)]
    private ?string $source = null;

    #[ORM\ManyToOne(targetEntity: EspnAthlete::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnAthlete $athlete = null;

    #[ORM\ManyToOne(targetEntity: EspnTeam::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnTeam $team = null;

    public static function buildFindByCriteriaFromDto(EspnNoteDto $dto, EspnAthlete|EspnTeam $parent): array
    {
        $criteria = [
            'espnId' => $dto->getId(),
        ];

        match (true) {
            $parent instanceof EspnAthlete => $criteria['athlete'] = $parent,
            $parent instanceof EspnTeam => $criteria['team'] = $parent,
        };

        return $criteria;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentType(): ?NoteParentTypeEnum
    {
        return $this->parentType;
    }

    public function setParentType(?NoteParentTypeEnum $parentType): static
    {
        $this->parentType = $parentType;
        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(?string $headline): static
    {
        $this->headline = $headline;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;
        return $this;
    }

    public function getAthlete(): ?EspnAthlete
    {
        return $this->athlete;
    }

    public function setAthlete(?EspnAthlete $athlete): static
    {
        $this->athlete = $athlete;
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
}
