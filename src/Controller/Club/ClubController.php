<?php

namespace App\Controller\Club;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\InfoWindow;

class ClubController extends AbstractController
{
    private const CLUB_LATITUDE = 50.47707987702586;
    private const CLUB_LONGITUDE = 3.6501962312217797;

    #[Route('/notre-histoire', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('club/about.html.twig', [
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        $clubLocation = new Point(self::CLUB_LATITUDE, self::CLUB_LONGITUDE);

        $myMap = (new Map())
            ->fitBoundsToMarkers()
            ->addMarker(new Marker(
                position: $clubLocation,
                title: 'Les Gais Carabiniers de Bernissart',
                infoWindow: new InfoWindow(
                    headerContent: '<h3>Les Gais Carabiniers de Bernissart</h3>',
                    content: '<p>Rue Lotard 16, 7320 Bernissart</p>'
                )
            ));

        return $this->render('club/contact.html.twig', [
            'myMap' => $myMap,
            'mapDirectionsUrl' => sprintf(
                'https://www.google.com/maps/search/?api=1&query=%.14F,%.16F',
                self::CLUB_LATITUDE,
                self::CLUB_LONGITUDE,
            ),
            'latitude' => self::CLUB_LATITUDE,
            'longitude' => self::CLUB_LONGITUDE,
        ]);
    }

    #[Route('/politique-de-confidentialite', name: 'app_privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('club/privacy_policy.html.twig', [
        ]);
    }
}
