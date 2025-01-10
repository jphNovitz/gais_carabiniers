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
            title: $facebookEvent->getTitle(),
            // slug: $facebookEvent->getSlug(),
            date: $facebookEvent->getDate(),
            facebookLink: $facebookEvent->getFacebookLink(),
            description: $facebookEvent->getDescription(),
            createdAt: $facebookEvent->getCreatedAt(),
            updatedAt: $facebookEvent->getUpdatedAt()
        );
    }

    public static function toEntity(FacebookEventDto $dto, ?FacebookEvent $facebookEvent = null): FacebookEvent
    {
        if (!$facebookEvent) {
            $facebookEvent = new FacebookEvent();
        }

        $facebookEvent->setTitle($dto->title);
        $facebookEvent->setDate($dto->date);
        $facebookEvent->setFacebookLink($dto->facebookLink);
        $facebookEvent->setDescription($dto->description);
        // $facebookEvent->setSlug($dto->slug);
        $facebookEvent->setCreatedAt($dto->createdAt);
        $facebookEvent->setUpdatedAt($dto->updatedAt);

        return $facebookEvent;
    }
}
