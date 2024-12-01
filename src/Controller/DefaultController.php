<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\InfoWindow;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(ClubRepository $clubRepository): Response
    {
        $myMap = (new Map());
        $myMap
            // Explicitly set the center and zoom
            ->center(new Point(50.472738, 3.661204))
//            ->zoom(6)

            // Or automatically fit the bounds to the markers
            ->fitBoundsToMarkers()
            ->addMarker(new Marker(
                position: new Point(50.472738, 3.661204),
//                title: 'Les Gais Carabiniers de Bernissart',
                infoWindow: new InfoWindow(
                    headerContent: '<h3>Les Gais Carabiniers de Bernissart</h3>',
                    content: '<p>Rue Lotard 16, 7320 Bernissart</p>'
                ),

//                icon: 'images/logo-gais-carabiniers-bernissart.webp'
            ))
            ->addMarker(new Marker(
                position: new Point(50.511097, 3.589901),
                title: 'Royale Fanfare Communale de Péruwelz'
            ))
            ->addMarker(new Marker(
                position: new Point(50.449078, 3.815871),
                title: 'Harmonie Royale de Saint-Ghislain'
            ))
            ->addMarker(new Marker(
                position: new Point(50.447907, 3.591055),
                title: 'Harmonie Municipale de Condé-sur-l\'Escaut'
            ))
            ->addMarker(new Marker(
                position: new Point(50.426841, 3.866219),
                title: 'La Bouverie',
                extra: ['icon_mask_url' => 'images/logo-gais-carabiniers-bernissart.webp']
//                extra: ['icon_mask_url' => 'https://maps.gstatic.com/mapfiles/place_api/icons/v2/tree_pinlet.svg']
            ))
        ;

        return $this->render('landing/index.html.twig', [
//            $clubs = $clubRepository->findAll()[0],
            'myMap' => $myMap
        ]);
    }
}
