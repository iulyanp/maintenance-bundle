<?php

namespace Iulyanp\MaintenanceBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class MaintenanceSubscriber.
 */
class MaintenanceSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $maintenance;

    /**
     * MaintenanceSubscriber constructor.
     *
     * @param Router $router
     * @param string $maintenance
     */
    public function __construct(Router $router, $maintenance)
    {
        $this->router = $router;
        $this->maintenance = $maintenance;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $requestUri = $request->getRequestUri();
        $currentRoute = $request->get('_route');
        $maintenanceRouteUri = $this->router->generate($this->maintenance['maintenance_route']);

        if ($this->isInMentenance() && !$this->isDueDate() && $this->maintenance['maintenance_route'] != $currentRoute) {
            $response = new RedirectResponse($maintenanceRouteUri);
            $event->setResponse($response);
        } elseif ((!$this->isInMentenance() || $this->isDueDate()) && $maintenanceRouteUri == $requestUri) {
            $response = new RedirectResponse('/');
            $event->setResponse($response);
        }
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    private function isInMentenance()
    {
        if (!$this->maintenance['enabled']) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    private function isDueDate()
    {
        if (!$this->maintenance['due_date']) {
            return false;
        }

        $currentDateTime = new \DateTime();
        if ($currentDateTime->getTimestamp() > strtotime($this->maintenance['due_date'])) {
            return true;
        }

        return false;
    }
}
