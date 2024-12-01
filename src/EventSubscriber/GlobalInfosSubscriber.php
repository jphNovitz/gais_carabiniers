<?php

namespace App\EventSubscriber;

use App\Repository\ClubRepository;

use http\Client\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class GlobalInfosSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ClubRepository $clubRepository,
        private readonly Environment     $twig,
        private RouterInterface          $router,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {

        return [
            KernelEvents::CONTROLLER => ['onKernelController']
        ];
    }

    public function onKernelController(ControllerEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');

        if ($route !== 'app_fallback' /*|| !str_contains($route, 'admin_')*/) {

            $infos = $this->clubRepository->findAll()[0];

//            if (null === $infos) {
//                $event->stopPropagation();
//                $event->setController(function () {
//                    return new RedirectResponse($this->router->generate('app_fallback'));
//                });
//                return;
//            }
//            $storeDto = new StoreDto();
//            $storeDto = $this->storeMapper->toDto($store);

            $this->twig->addGlobal('globalInfos', $infos);
        }
    }
}