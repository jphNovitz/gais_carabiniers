<?php

namespace App\Enum;

enum ShootingCategory: string
{
    case CLASSIC = 'classic';
    case SUPPORTED = 'supported';

    public static function fromUsesSupport(?bool $usesSupport): self
    {
        return $usesSupport ? self::SUPPORTED : self::CLASSIC;
    }
}
