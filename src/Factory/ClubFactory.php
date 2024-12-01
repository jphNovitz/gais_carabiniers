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
        $faker = self::faker('fr_BE');
        return [
            'description' => $faker->text(),
            'email' => "info@gaicarabiniers.be",
            'phoneNumber' => self::faker()->phoneNumber(),
            'name' => "Les Gais Carabiniers",
            'federationNumber' => "7-044",
            'street' => "Rue Lotard",
            'streetNumber' => "16",
            'postCode' => "7320",
            'city' => "Bernissart",
            'logoName' => "logo-gais-carabiniers-bernissart.webp",
            'createdAt' => self::faker()->dateTimeBetween('-1 years', 'now'),
            'updatedAt' => self::faker()->dateTimeBetween('-1 years', 'now'),
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
