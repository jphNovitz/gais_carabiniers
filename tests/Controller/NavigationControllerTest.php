<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

final class NavigationControllerTest extends WebTestCase
{
    public function testMobileMenuWorksWithoutJavaScript(): void
    {
        self::bootKernel();

        $request = Request::create('/faq');
        $request->attributes->set('_route', 'app_faq');

        /** @var RequestStack $requestStack */
        $requestStack = static::getContainer()->get(RequestStack::class);
        $requestStack->push($request);

        try {
            /** @var Environment $twig */
            $twig = static::getContainer()->get(Environment::class);
            $crawler = new Crawler($twig->render('_header/_header.html.twig', [
                'globalInfos' => null,
            ]));
        } finally {
            $requestStack->pop();
        }

        self::assertCount(1, $crawler->filter('details#mobile-main-menu > summary.swap.swap-rotate'));
        self::assertCount(1, $crawler->filter('details#mobile-main-menu > #mobile-menu-2'));
        self::assertCount(0, $crawler->filter('[data-collapse-toggle="mobile-menu-2"]'));
    }

    public function testCompetitionsMenuWorksWithoutJavaScript(): void
    {
        self::bootKernel();

        $request = Request::create('/faq');
        $request->attributes->set('_route', 'app_faq');

        /** @var RequestStack $requestStack */
        $requestStack = static::getContainer()->get(RequestStack::class);
        $requestStack->push($request);

        try {
            /** @var Environment $twig */
            $twig = static::getContainer()->get(Environment::class);
            $crawler = new Crawler($twig->render('_header/_header.html.twig', [
                'globalInfos' => null,
            ]));
        } finally {
            $requestStack->pop();
        }

        self::assertCount(1, $crawler->filter('details#competitions-menu > summary'));
        self::assertCount(5, $crawler->filter('details#competitions-menu a'));
    }
}
