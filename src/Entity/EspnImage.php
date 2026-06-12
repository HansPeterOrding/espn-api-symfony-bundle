<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\ImageParentTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnImageRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnImage as EspnImageDto;

#[ORM\Entity(repositoryClass: EspnImageRepository::class)]
#[ORM\Table(name: 'easb_espn_image')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(name: 'idx_espn_image_parent_type', columns: ['parent_type'])]
class EspnImage
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true, enumType: ImageParentTypeEnum::class)]
    private ?ImageParentTypeEnum $parentType = null;

    #[ORM\Column(nullable: true)]
    private ?string $href = null;

    #[ORM\Column(nullable: true)]
    private ?int $width = null;

    #[ORM\Column(nullable: true)]
    private ?int $height = null;

    #[ORM\Column(nullable: true)]
    private ?string $alt = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $rel = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $lastUpdated = null;

    #[ORM\ManyToOne(targetEntity: EspnTeam::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnTeam $team = null;

    #[ORM\ManyToOne(targetEntity: EspnVenue::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnVenue $venue = null;

    public static function buildFindByCriteriaFromDto(EspnImageDto $dto, EspnTeam|EspnVenue $parent): array
    {
        $criteria = [
            'href' => $dto->getHref(),
        ];

        match (true) {
            $parent instanceof EspnTeam => $criteria['team'] = $parent,
            $parent instanceof EspnVenue => $criteria['venue'] = $parent,
        };

        return $criteria;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentType(): ?ImageParentTypeEnum
    {
        return $this->parentType;
    }

    public function setParentType(?ImageParentTypeEnum $parentType): static
    {
        $this->parentType = $parentType;
        return $this;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): static
    {
        $this->href = $href;
        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): static
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): static
    {
        $this->height = $height;
        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): static
    {
        $this->alt = $alt;
        return $this;
    }

    public function getRel(): ?array
    {
        return $this->rel;
    }

    public function setRel(?array $rel): static
    {
        $this->rel = $rel;
        return $this;
    }

    public function getLastUpdated(): ?DateTimeImmutable
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(?DateTimeImmutable $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;
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

    public function getVenue(): ?EspnVenue
    {
        return $this->venue;
    }

    public function setVenue(?EspnVenue $venue): static
    {
        $this->venue = $venue;
        return $this;
    }
}
