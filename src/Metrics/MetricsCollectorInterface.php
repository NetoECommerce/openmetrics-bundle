<?php

namespace Neto\OpenmetricsBundle\Metrics;

interface MetricsCollectorInterface
{
    public function inc($name, $description = '', array $tags = []): void;
    public function incBy($value, $name, $description = '', array $tags = []): void;
    public function gauge($value, $name, $description = '', array $tags = []): void;
}