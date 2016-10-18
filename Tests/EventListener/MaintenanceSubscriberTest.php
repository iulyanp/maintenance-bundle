<?php

/**
 * Created by PhpStorm.
 * User: iulianp
 * Date: 15.10.2016
 * Time: 19:14
 */
class MaintenanceSubscriberTest extends \PHPUnit_Framework_TestCase
{
    const MAINTENANCE_ROUTE_URI = '/maintenance';
    const MAINTENANCE_ROUTE = 'iulyanp_maintenance_homepage';

    const TEST_ROUTE_URI = '/test';
    const TEST_ROUTE = 'iulyanp_maintenance_test';

    /**
     * @test
     */
    public function onKernelRequestRedirectToMaintenance()
    {
        $request = $this->getStub(
            'Symfony\Component\HttpFoundation\Request',
            [
                'getRequestUri' => self::TEST_ROUTE_URI,
                'get'           => self::TEST_ROUTE,
            ]
        );

        $event = $this->getStub(
            'Symfony\Component\HttpKernel\Event\GetResponseEvent',
            [
                'isMasterRequest' => true,
                'getRequest'      => $request,
                'setResponse'     => new \Symfony\Component\HttpFoundation\RedirectResponse(
                    self::MAINTENANCE_ROUTE_URI
                ),
                'getResponse'     => new \Symfony\Component\HttpFoundation\RedirectResponse(
                    self::MAINTENANCE_ROUTE_URI
                ),
            ]
        );

        $router = $this->getStub(
            'Symfony\Bundle\FrameworkBundle\Routing\Router',
            [
                'generate' => self::MAINTENANCE_ROUTE_URI,
            ]
        );

        $subscriber = new \Iulyanp\MaintenanceBundle\EventListener\MaintenanceSubscriber(
            $router, [
                'enabled'           => true,
                'due_date'          => (new \DateTime('+1 day'))->format('d-m-Y H:i:s'),
                'maintenance_route' => self::MAINTENANCE_ROUTE,
            ]
        );

        $subscriber->onKernelRequest($event);

        $this->assertEquals(self::MAINTENANCE_ROUTE_URI, $event->getResponse()->getTargetUrl());
        $this->assertContains(
            sprintf('Redirecting to <a href="%s">%s</a>', self::MAINTENANCE_ROUTE_URI, self::MAINTENANCE_ROUTE_URI),
            $event->getResponse()->getContent()
        );
    }

    /**
     * @test
     */
    public function onKernelRequestNormallyRedirect()
    {
        $request = $this->getStub(
            'Symfony\Component\HttpFoundation\Request',
            [
                'getRequestUri' => self::TEST_ROUTE_URI,
                'get'           => self::TEST_ROUTE,
            ]
        );

        $event = $this->getStub(
            'Symfony\Component\HttpKernel\Event\GetResponseEvent',
            [
                'isMasterRequest' => true,
                'getRequest'      => $request,
                'setResponse'     => new \Symfony\Component\HttpFoundation\RedirectResponse(self::TEST_ROUTE_URI),
                'getResponse'     => new \Symfony\Component\HttpFoundation\RedirectResponse(self::TEST_ROUTE_URI),
            ]
        );

        $router = $this->getStub(
            'Symfony\Bundle\FrameworkBundle\Routing\Router',
            [
                'generate' => self::MAINTENANCE_ROUTE_URI,
            ]
        );

        $subscriber = new \Iulyanp\MaintenanceBundle\EventListener\MaintenanceSubscriber(
            $router, [
                'enabled'           => false,
                'due_date'          => (new \DateTime('+1 day'))->format('d-m-Y H:i:s'),
                'maintenance_route' => self::MAINTENANCE_ROUTE,
            ]
        );

        $subscriber->onKernelRequest($event);

        $this->assertEquals(self::TEST_ROUTE_URI, $event->getResponse()->getTargetUrl());
        $this->assertContains(
            sprintf('Redirecting to <a href="%s">%s</a>', self::TEST_ROUTE_URI, self::TEST_ROUTE_URI),
            $event->getResponse()->getContent()
        );
    }

    /**
     * @test
     */
    public function onKernelRequestRedirectToHomepageWhenMaintenanceDisabled()
    {
        $request = $this->getStub(
            'Symfony\Component\HttpFoundation\Request',
            [
                'getRequestUri' => self::MAINTENANCE_ROUTE_URI,
                'get'           => self::MAINTENANCE_ROUTE,
            ]
        );

        $event = $this->getStub(
            'Symfony\Component\HttpKernel\Event\GetResponseEvent',
            [
                'isMasterRequest' => true,
                'getRequest'      => $request,
                'setResponse'     => new \Symfony\Component\HttpFoundation\RedirectResponse('/'),
                'getResponse'     => new \Symfony\Component\HttpFoundation\RedirectResponse('/'),
            ]
        );

        $router = $this->getStub(
            'Symfony\Bundle\FrameworkBundle\Routing\Router',
            [
                'generate' => self::MAINTENANCE_ROUTE_URI,
            ]
        );

        $subscriber = new \Iulyanp\MaintenanceBundle\EventListener\MaintenanceSubscriber(
            $router, [
                'enabled'           => false,
                'due_date'          => (new \DateTime('-1 day'))->format('d-m-Y H:i:s'),
                'maintenance_route' => self::MAINTENANCE_ROUTE,
            ]
        );

        $subscriber->onKernelRequest($event);

        $this->assertEquals('/', $event->getResponse()->getTargetUrl());
        $this->assertContains('Redirecting to <a href="/">/</a>', $event->getResponse()->getContent());
    }

    /**
     * @param Object $class
     * @param array  $getters
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getStub($class, array $getters = [])
    {
        $stub = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();

        foreach ($getters as $method => $value) {
            $stub->method($method)->willReturn($value);
        }

        return $stub;
    }
}
