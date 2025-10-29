<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnTeamRecordItemTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRecordItemRepository;

#[ORM\Entity(repositoryClass: EspnTeamRecordItemRepository::class)]
class EspnTeamRecordItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: EspnTeamRecordItemTypeEnum::class)]
    private ?EspnTeamRecordItemTypeEnum $type = null;

    #[ORM\Column(length: 255)]
    private ?string $summary = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $stats = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnTeamRecord $espnTeamRecord = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): EspnTeamRecordItem
    {
        $this->description = $description;
        return $this;
    }

    public function getType(): ?EspnTeamRecordItemTypeEnum
    {
        return $this->type;
    }

    public function setType(EspnTeamRecordItemTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return EspnTeamRecordStat[]
     */
    public function getStats(): array {
        return array_map(
            static function (array $stat): EspnTeamRecordStat {
                $r = new EspnTeamRecordStat();
                $r->setName($stat['name'] ?? '');
                $r->setValue($stat['value'] ?? null);
                return $r;
            },
            $this->stats
        );
    }

    /**
     * @param EspnTeamRecordStat[] $collection
     */
    public function setStats(array $collection): void {
        $this->stats = array_map(
            fn(EspnTeamRecordStat $a) => ['name'=>$a->getName(), 'value'=>$a->getValue()],
            $collection
        );
    }

    public function getEspnTeamRecord(): ?EspnTeamRecord
    {
        return $this->espnTeamRecord;
    }

    public function setEspnTeamRecord(?EspnTeamRecord $espnTeamRecord): static
    {
        $this->espnTeamRecord = $espnTeamRecord;

        return $this;
    }
}
