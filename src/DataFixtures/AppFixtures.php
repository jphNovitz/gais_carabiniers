<?php

namespace App\DataFixtures;

use App\DataFixtures\Story\DefaultStory;
use Zenstruck\Foundry\Story;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\MemberFactory;
use App\Factory\MeetingFactory;
use App\Factory\MeetingParticipantFactory;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//        DefaultStory::load();
        MemberFactory::new()->createMany(10);
        MeetingFactory::new()
            ->create([
                'participants' => MeetingParticipantFactory::new()
                    ->many(5, function() {
                        return [
                            'shooter'  => MemberFactory::random(),
                            'present'  => self::faker()->boolean(),
                            'position' => self::faker()->unique()->numberBetween(1, 100),
                        ];
                    })
            ]);
    }
}
