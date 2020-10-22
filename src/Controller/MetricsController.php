<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\Controller;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Symfony\Component\HttpFoundation\Response;

class MetricsController
{
    /**
     * @var CollectorRegistry
     */
    private $collectionRegistry;

    public function __construct(CollectorRegistry $collectionRegistry)
    {
        $this->collectionRegistry = $collectionRegistry;
    }

    public function index(): Response
    {
        $renderer = new RenderTextFormat();
        $metrics = $this->collectionRegistry->getMetricFamilySamples();

        return new Response(
            $renderer->render($metrics),
            200,
            ['Content-type' => RenderTextFormat::MIME_TYPE]);
    }
}