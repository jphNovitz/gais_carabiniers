<?php

namespace App\Mapper;

use App\Entity\Club;
use App\Dto\ClubDto;

class ClubMapper
{
    public static function fromEntity(Club $club): ClubDto
    {
        return new ClubDto(
            id: $club->getId(),
            name: $club->getName(),
            federationNumber: $club->getFederationNumber(),
            phoneNumber: $club->getPhoneNumber(),
            description: $club->getDescription(),
            logoName: $club->getLogoName(),
            logoSize: $club->getLogoSize(),
            imageName: $club->getImageName(),
            imageSize: $club->getImageSize(),
            createdAt: $club->getCreatedAt(),
            updatedAt: $club->getUpdatedAt(),
            street: $club->getStreet(),
            streetNumber: $club->getStreetNumber(),
            postCode: $club->getPostCode(),
            city: $club->getCity(),
            email: $club->getEmail(),
            slug: $club->getSlug()
        );
    }

    public static function toEntity(Club $club, ClubDto $dto): Club
    {
        $club->setName($dto->name);
        $club->setFederationNumber($dto->federationNumber);
        $club->setPhoneNumber($dto->phoneNumber);
        $club->setDescription($dto->description);
        $club->setLogoFile($dto->logoFile);
        $club->setLogoName($dto->logoName);
        $club->setLogoSize($dto->logoSize);
        $club->setImageName($dto->imageName);
        $club->setImageSize($dto->imageSize);
        $club->setCreatedAt($dto->createdAt);
        $club->setUpdatedAt($dto->updatedAt);
        $club->setStreet($dto->street);
        $club->setStreetNumber($dto->streetNumber);
        $club->setPostCode($dto->postCode);
        $club->setCity($dto->city);
        $club->setEmail($dto->email);
        $club->setSlug($dto->slug);

        return $club;
    }
}
