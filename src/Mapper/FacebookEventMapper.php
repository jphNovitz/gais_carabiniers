<?php

namespace App\Mapper;

use App\Dto\FacebookEventDto;
use App\Entity\FacebookEvent;

class FacebookEventMapper
{
    public static function fromEntity(FacebookEvent $facebookEvent): FacebookEventDto
    {
        return new FacebookEventDto(
            id: $facebookEvent->getId(),
            name: $facebookEvent->getName(),
            slug: $facebookEvent->getSlug(),
            date: $facebookEvent->getDate(),
            facebookLink: $facebookEvent->getFacebookLink(),
            createdAt: $facebookEvent->getCreatedAt(),
            updatedAt: $facebookEvent->getUpdatedAt()
        );
    }

    public static function toEntity(FacebookEventDto $dto, ?FacebookEvent $facebookEvent = null): FacebookEvent
    {
        if (!$facebookEvent) {
            $facebookEvent = new FacebookEvent();
        }

        $facebookEvent->setName($dto->name);
        $facebookEvent->setDate($dto->date);
        $facebookEvent->setFacebookLink($dto->facebookLink);
        $facebookEvent->setSlug($dto->slug);
        $facebookEvent->setCreatedAt($dto->createdAt);
        $facebookEvent->setUpdatedAt($dto->updatedAt);

        return $facebookEvent;
    }
}
