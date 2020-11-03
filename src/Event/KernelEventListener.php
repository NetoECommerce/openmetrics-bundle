<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\Event;

use Neto\OpenmetricsBundle\Metrics\MetricsCollectorInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

/**
 * Class KernelEventListener
 *
 * Collects application request and error metrics using the request and exception events
 *
 * @package Neto\OpenmetricsBundle\Event
 */
class KernelEventListener
{
    /** @var MetricsCollectorInterface */
    private $collector;

    /** @var array */
    private $ignoredRoutes;

    public function __construct(MetricsCollectorInterface $collector, array $ignoredRoutes = [])
    {
        $this->collector = $collector;
        $this->ignoredRoutes = $ignoredRoutes;
    }

    /**
     * Increments the request counter
     *
     * @param KernelEvent $event
     * @return void
     */
    public function onKernelRequest(KernelEvent $event): void
    {
        if (!$event->isMasterRequest() || $this->isIgnoredRoute($event)) {
            return;
        }

        $this->collector->inc('request_count', 'total request count');
    }

    /**
     * Increments the error count and tags it with the current route
     *
     * @param KernelEvent $event
     * @return void
     */
    public function onKernelException(KernelEvent $event) {
        $request = $event->getRequest();
        $routeName = $request->getMethod() . '-undefined';
        if ($request->attributes->get('_route')) {
            $routeName = sprintf('%s-%s', $request->getMethod(), $request->attributes->get('_route'));
        }

        $this->collector->inc(
            "error_count",
            "count of error responses",
            [ 'route' => $routeName ]
        );
    }

    /**
     * Returns true if the current route matches a route in the ignored_routes config
     *
     * @param KernelEvent $event
     * @return bool
     */
    private function isIgnoredRoute(KernelEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');

        return in_array($route, $this->ignoredRoutes);
    }
}
