<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnTeamRecord as EspnTeamRecordDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeamRecord as EspnTeamRecordEntity;

class EspnTeamRecordConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnTeamRecordItemConverter $teamRecordItemConverter
    )
    {
    }

    public function toEntity(EspnTeamRecordDto $espnTeamRecordDto, $espnTeamRecordEntity = null): EspnTeamRecordEntity
    {
        if (!$espnTeamRecordEntity) {
            $espnTeamRecordEntity = new EspnTeamRecordEntity();
        }

        $espnTeamRecordEntity->removeAllItems();

        foreach($espnTeamRecordDto->getItems() as $item){
            $itemEntity = $this->teamRecordItemConverter->toEntity($item);
            $espnTeamRecordEntity->addItem($itemEntity);
        }

        return $espnTeamRecordEntity;
    }
}
