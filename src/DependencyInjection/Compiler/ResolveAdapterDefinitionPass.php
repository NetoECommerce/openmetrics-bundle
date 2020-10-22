<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\DependencyInjection\Compiler;

use Prometheus\Storage\APC;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\Redis;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveAdapterDefinitionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('neto_openmetrics.adapter')) {
            return;
        }

        $adapterClasses = [
            'in_memory' => InMemory::class,
            'apcu' => APC::class,
            'redis' => Redis::class,
        ];

        $definition = $container->getDefinition('neto_openmetrics.adapter');
        $type = $container->getParameter('neto_openmetrics.type');
        $definition->setClass($adapterClasses[$type]);
        if ($type === 'redis') {
            $definition->setArguments([$container->getParameter('neto_openmetrics.redis')]);
        }
    }
}