<?php

namespace App\Tests\Controller;

use App\Entity\Club;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ClubControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/club/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Club::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Club index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'club[name]' => 'Testing',
            'club[federationNumber]' => 'Testing',
            'club[phoneNumber]' => 'Testing',
            'club[description]' => 'Testing',
            'club[logoName]' => 'Testing',
            'club[logoSize]' => 'Testing',
            'club[imageName]' => 'Testing',
            'club[imageSize]' => 'Testing',
            'club[createdAt]' => 'Testing',
            'club[updatedAt]' => 'Testing',
            'club[street]' => 'Testing',
            'club[streetNumber]' => 'Testing',
            'club[postCode]' => 'Testing',
            'club[city]' => 'Testing',
            'club[email]' => 'Testing',
            'club[slug]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Club();
        $fixture->setName('My Title');
        $fixture->setFederationNumber('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setDescription('My Title');
        $fixture->setLogoName('My Title');
        $fixture->setLogoSize('My Title');
        $fixture->setImageName('My Title');
        $fixture->setImageSize('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setStreet('My Title');
        $fixture->setStreetNumber('My Title');
        $fixture->setPostCode('My Title');
        $fixture->setCity('My Title');
        $fixture->setEmail('My Title');
        $fixture->setSlug('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Club');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Club();
        $fixture->setName('Value');
        $fixture->setFederationNumber('Value');
        $fixture->setPhoneNumber('Value');
        $fixture->setDescription('Value');
        $fixture->setLogoName('Value');
        $fixture->setLogoSize('Value');
        $fixture->setImageName('Value');
        $fixture->setImageSize('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setStreet('Value');
        $fixture->setStreetNumber('Value');
        $fixture->setPostCode('Value');
        $fixture->setCity('Value');
        $fixture->setEmail('Value');
        $fixture->setSlug('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'club[name]' => 'Something New',
            'club[federationNumber]' => 'Something New',
            'club[phoneNumber]' => 'Something New',
            'club[description]' => 'Something New',
            'club[logoName]' => 'Something New',
            'club[logoSize]' => 'Something New',
            'club[imageName]' => 'Something New',
            'club[imageSize]' => 'Something New',
            'club[createdAt]' => 'Something New',
            'club[updatedAt]' => 'Something New',
            'club[street]' => 'Something New',
            'club[streetNumber]' => 'Something New',
            'club[postCode]' => 'Something New',
            'club[city]' => 'Something New',
            'club[email]' => 'Something New',
            'club[slug]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/club/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getFederationNumber());
        self::assertSame('Something New', $fixture[0]->getPhoneNumber());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getLogoName());
        self::assertSame('Something New', $fixture[0]->getLogoSize());
        self::assertSame('Something New', $fixture[0]->getImageName());
        self::assertSame('Something New', $fixture[0]->getImageSize());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getStreet());
        self::assertSame('Something New', $fixture[0]->getStreetNumber());
        self::assertSame('Something New', $fixture[0]->getPostCode());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getSlug());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Club();
        $fixture->setName('Value');
        $fixture->setFederationNumber('Value');
        $fixture->setPhoneNumber('Value');
        $fixture->setDescription('Value');
        $fixture->setLogoName('Value');
        $fixture->setLogoSize('Value');
        $fixture->setImageName('Value');
        $fixture->setImageSize('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setStreet('Value');
        $fixture->setStreetNumber('Value');
        $fixture->setPostCode('Value');
        $fixture->setCity('Value');
        $fixture->setEmail('Value');
        $fixture->setSlug('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/club/');
        self::assertSame(0, $this->repository->count([]));
    }
}
