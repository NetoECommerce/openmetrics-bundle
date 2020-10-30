<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\Metrics;

use Prometheus\CollectorRegistry;

class PrometheusCollector implements MetricsCollectorInterface
{
    /** @var string */
    private $namespace;

    /** @var CollectorRegistry */
    private $collectorRegistry;

    public function __construct($namespace, CollectorRegistry $collectorRegistry)
    {
        $this->namespace = $namespace;
        $this->collectorRegistry = $collectorRegistry;
    }

    public function inc($name, $description= '', array $tags = []): void
    {
        $this->incBy(1, $name, $description, $tags);
    }

    public function incBy($value, $name, $description= '', array $tags = []): void
    {
        $counter = $this->collectorRegistry->getOrRegisterCounter(
            $this->namespace,
            $name,
            $description,
            array_keys($tags)
        );
        $counter->incBy($value, array_values($tags));
    }

    public function gauge($value, $name, $description = '', array $tags = []): void
    {
        $guage = $this->collectorRegistry->getOrRegisterGauge(
            $this->namespace,
            $name,
            $description,
            array_keys($tags)
        );
        $guage->set($value, array_values($tags));
    }
}
