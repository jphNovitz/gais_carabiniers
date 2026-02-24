<?php

namespace App\Tests\Controller\Meeting;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MeetingcontrollerControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/meeting/meetingcontroller');

        self::assertResponseIsSuccessful();
    }
}
