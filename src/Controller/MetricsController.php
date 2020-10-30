<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\Controller;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Symfony\Component\HttpFoundation\Response;

class MetricsController
{
    /** @var CollectorRegistry */
    private $collectorRegistry;

    public function __construct(CollectorRegistry $collectorRegistry)
    {
        $this->collectorRegistry = $collectorRegistry;
    }

    public function index(): Response
    {
        $renderer = new RenderTextFormat();
        $metrics = $this->collectorRegistry->getMetricFamilySamples();

        return new Response(
            $renderer->render($metrics),
            200,
            [ 'Content-type' => RenderTextFormat::MIME_TYPE ]
        );
    }
}