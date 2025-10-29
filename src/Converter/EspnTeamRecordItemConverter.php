<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnTeamRecordItem as EspnTeamRecordItemDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnTeamRecordItemTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeamRecordItem as EspnTeamRecordItemEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRecordItemRepository;

class EspnTeamRecordItemConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnTeamRecordStatConverter $teamRecordStatConverter,
    )
    {
    }

    public function toEntity(EspnTeamRecordItemDto $espnTeamRecordItemDto, EspnTeamRecordItemEntity $espnTeamRecordItemEntity = null): EspnTeamRecordItemEntity
    {
        if(!$espnTeamRecordItemEntity) {
            $espnTeamRecordItemEntity = new EspnTeamRecordItemEntity();
        }

        $espnTeamRecordItemEntity->setDescription($espnTeamRecordItemDto->getDescription());
        $espnTeamRecordItemEntity->setType(EspnTeamRecordItemTypeEnum::from($espnTeamRecordItemDto->getType()));
        $espnTeamRecordItemEntity->setSummary($espnTeamRecordItemDto->getSummary());

        $statEntities = [];
        foreach($espnTeamRecordItemDto->getStats() as $stat) {
            $statEntities[] = $this->teamRecordStatConverter->toEntity($stat);
        }
        $espnTeamRecordItemEntity->setStats($statEntities);

        return $espnTeamRecordItemEntity;
    }
}
