<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\Metrics;

/**
 * Interface MetricsCollectorInterface
 *
 * Abstraction layer for metrics collection in case we need to swap out the client library.
 *
 * @todo Add histogram support!
 * @package Neto\OpenmetricsBundle\Metrics
 */
interface MetricsCollectorInterface
{
    /**
     * Increments a counter by one
     *
     * @param string $name        Name of the metric eg: requests_total
     * @param string $description Short description  eg: Total request count
     * @param array  $tags        Metric metadata    eg: method => GET
     */
    public function inc($name, $description = '', array $tags = []): void;

    /**
     * Increments a counter by $value
     *
     * @param int    $value       Increase counter by $value
     * @param string $name        Name of the metric eg: requests_total
     * @param string $description Short description  eg: Total request count
     * @param array  $tags        Metric metadata    eg: method => GET
     */
    public function incBy($value, $name, $description = '', array $tags = []): void;

    /**
     * Sets gauge to $value
     *
     * @param int    $value
     * @param string $name        Name of the gauge eg: memory_usage
     * @param string $description Short description eg: Memory usage in bytes
     * @param array  $tags        Metric metadata
     */
    public function gauge($value, $name, $description = '', array $tags = []): void;
}