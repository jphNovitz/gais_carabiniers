<?php

namespace App\Contract;

interface CategorizedStandingBuilderInterface
{
    public function categorizeMeetingStanding(array $standing): array;
}
