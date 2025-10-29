<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRecordRepository;

#[ORM\Entity(repositoryClass: EspnTeamRecordRepository::class)]
class EspnTeamRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, EspnTeamRecordItem>
     */
    #[ORM\OneToMany(mappedBy: 'espnTeamRecord', targetEntity: EspnTeamRecordItem::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, EspnTeamRecordItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(EspnTeamRecordItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setEspnTeamRecord($this);
        }

        return $this;
    }

    public function removeItem(EspnTeamRecordItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getEspnTeamRecord() === $this) {
                $item->setEspnTeamRecord(null);
            }
        }

        return $this;
    }

    public function removeAllItems(): static
    {
        foreach ($this->items as $item) {
            $this->removeItem($item);
        }

        return $this;
    }
}
