<?php

namespace App\Factory;

use App\Entity\Club;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Club>
 */
final class ClubFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Club::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'email' => "info@gaicarabiniers.be",
            'phoneNumber' => self::faker()->phoneNumber(),
            'name' => "Les Gai Carabiniers de Bernissart",
            'federationNumber' => "7-044",
            'street' => "Rue Lotard",
            'streetNumber' => "16",
            'postCode' => "7320",
            'city' => "Bernissart",
            'logoName' => "logo-gais-carabiniers-bernissart.webp",
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Club $club): void {})
        ;
    }
}
