<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnNote as EspnNoteDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnNote as EspnNoteEntity;

class EspnNoteConverter implements ConverterInterface
{
    public function toEntity(EspnNoteDto $espnNoteDto, $espnNoteEntity = null): EspnNoteEntity
    {
        if (!$espnNoteEntity) {
            $espnNoteEntity = new EspnNoteEntity();
        }

        $espnNoteEntity->setType($espnNoteDto->getType());
        $espnNoteEntity->setHeadline($espnNoteDto->getHeadline());

        return $espnNoteEntity;
    }
}
