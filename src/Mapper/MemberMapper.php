<?php

namespace App\Mapper;


use App\Dto\MemberDto;
use App\Entity\Member;

class MemberMapper
{
    public static function fromEntity(Member $member): MemberDto
    {
        return new MemberDto(
            id: $member->getId(),
            firstName: $member->getFirstName(),
            lastName: $member->getLastName(),
            email: $member->getEmail(),
            phone: $member->getPhone(),
            street: $member->getStreet(),
            streetNumber: $member->getStreetNumber(),
            postalCode: $member->getPostalCode(),
            city: $member->getCity(),
            slug: $member->getSlug(),
            isActive: $member->isActive(),
            usesSupport: $member->isUsesSupport(),
            createdAt: $member->getCreatedAt(),
            updatedAt: $member->getUpdatedAt()
        );
    }

    public static function toEntity(MemberDto $dto, ?Member $member = null): Member
    {
        if (!$member) {
            $member = new Member();
        }

        $member->setFirstName($dto->firstName);
        $member->setLastName($dto->lastName);
        $member->setEmail($dto->email);
        $member->setPhone($dto->phone);
        $member->setStreet($dto->street);
        $member->setStreetNumber($dto->streetNumber);
        $member->setPostalCode($dto->postalCode);
        $member->setCity($dto->city);
        $member->setIsActive($dto->isActive);
        $member->setUsesSupport($dto->usesSupport);
        $member->setCreatedAt($dto->createdAt);
        $member->setUpdatedAt($dto->updatedAt);

        return $member;
    }

}
