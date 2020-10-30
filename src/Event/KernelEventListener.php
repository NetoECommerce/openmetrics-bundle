<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\Event;

use Neto\OpenmetricsBundle\Metrics\MetricsCollectorInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

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

    public function onKernelResponse(KernelEvent $event): void
    {
        $request = $event->getRequest();
        if (
            !$event->isMasterRequest()
            || $this->isIgnoredRoute($event)
            || !in_array($request->getMethod(), [ 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' ])
        ) {
            return;
        }

        $route = 'undefined';
        if ($request->getMethod() && $request->attributes->get('_route')) {
            $route = sprintf('%s-%s', $request->getMethod(), $request->attributes->get('_route'));
        }

        $status = $event->getResponse()->getStatusCode();
        $maskedStatus = 'undefined';
        if ($status >= 200 && $status <= 500) {
            $maskedStatus = substr((string)$status, 0, 1) . 'xx';
        }

        $this->collector->inc(
            'request_count',
            'total request count',
            [ 'route' => $route, 'status' => $maskedStatus ]
        );
    }

    private function isIgnoredRoute(KernelEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');

        return in_array($route, $this->ignoredRoutes);
    }
}
