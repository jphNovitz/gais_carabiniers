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
        $member->setIsActive($dto->isActive);
        $member->setUsesSupport($dto->usesSupport);
        $member->setCreatedAt($dto->createdAt);
        $member->setUpdatedAt($dto->updatedAt);

        return $member;
    }

}
