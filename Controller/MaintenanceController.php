<?php

namespace Iulyanp\MaintenanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class MaintenanceController.
 */
class MaintenanceController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('IulyanpMaintenanceBundle:Maintenance:index.html.twig', [
            'signature'   => $this->getParameter('iulyanp_maintenance.signature'),
            'title'       => $this->getParameter('iulyanp_maintenance.title'),
            'description' => $this->getParameter('iulyanp_maintenance.description'),
        ]);
    }
}
