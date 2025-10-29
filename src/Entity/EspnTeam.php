<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnTeam as EspnTeamDto;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;

#[ORM\Entity(repositoryClass: EspnTeamRepository::class)]
class EspnTeam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $teamId = null;

    #[ORM\Column(length: 255)]
    private ?string $uid = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $displayName = null;

    #[ORM\Column(length: 255)]
    private ?string $shortDisplayName = null;

    #[ORM\Column(length: 255)]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(nullable: true)]
    private ?string $alternateId = null;

    /**
     * @var Collection<int, EspnImage>
     */
    #[ORM\OneToMany(mappedBy: 'espnTeam', targetEntity: EspnImage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $logos;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?EspnTeamRecord $record = null;

    #[ORM\OneToOne(inversedBy: 'team', cascade: ['persist', 'remove'])]
    private ?EspnFranchise $franchise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $standingSummary = null;

    /**
     * @var Collection<int, EspnSchedule>
     */
    #[ORM\OneToMany(mappedBy: 'team', targetEntity: EspnSchedule::class)]
    private Collection $schedules;

    public function __construct()
    {
        $this->logos = new ArrayCollection();
        $this->schedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamId(): ?string
    {
        return $this->teamId;
    }

    public function setTeamId(string $teamId): static
    {
        $this->teamId = $teamId;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): static
    {
        $this->uid = $uid;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getShortDisplayName(): ?string
    {
        return $this->shortDisplayName;
    }

    public function setShortDisplayName(string $shortDisplayName): static
    {
        $this->shortDisplayName = $shortDisplayName;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getAlternateId(): ?string
    {
        return $this->alternateId;
    }

    public function setAlternateId(?string $alternateId): EspnTeam
    {
        $this->alternateId = $alternateId;
        return $this;
    }

    /**
     * @return Collection<int, EspnImage>
     */
    public function getLogos(): Collection
    {
        return $this->logos;
    }

    public function addLogo(EspnImage $logo): static
    {
        if (!$this->logos->contains($logo)) {
            $this->logos->add($logo);
            $logo->setEspnTeam($this);
        }

        return $this;
    }

    public function removeLogo(EspnImage $logo): static
    {
        if ($this->logos->removeElement($logo)) {
            // set the owning side to null (unless already changed)
            if ($logo->getEspnTeam() === $this) {
                $logo->setEspnTeam(null);
            }
        }

        return $this;
    }

    public function removeAllLogos(): static
    {
        foreach($this->logos as $logo) {
            $this->removeLogo($logo);
        }

        return $this;
    }

    public function getRecord(): ?EspnTeamRecord
    {
        return $this->record;
    }

    public function setRecord(?EspnTeamRecord $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function getFranchise(): ?EspnFranchise
    {
        return $this->franchise;
    }

    public function setFranchise(?EspnFranchise $franchise): static
    {
        $this->franchise = $franchise;

        return $this;
    }

    public function getStandingSummary(): ?string
    {
        return $this->standingSummary;
    }

    public function setStandingSummary(?string $standingSummary): static
    {
        $this->standingSummary = $standingSummary;

        return $this;
    }

    /**
     * @return Collection<int, EspnSchedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(EspnSchedule $schedule): static
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setTeam($this);
        }

        return $this;
    }

    public function removeSchedule(EspnSchedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getTeam() === $this) {
                $schedule->setTeam(null);
            }
        }

        return $this;
    }

    public function buildFindByCriteriaFromDto(EspnTeamDto $espnTeamDto): array
    {
        /** @todo: implement */
        return [
            'teamId' => $espnTeamDto->getId()
        ];
    }
}
