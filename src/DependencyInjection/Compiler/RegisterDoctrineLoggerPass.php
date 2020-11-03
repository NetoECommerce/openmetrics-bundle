<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\DependencyInjection\Compiler;

use Neto\OpenmetricsBundle\DBAL\Logging\DoctrineQueryMetricsLogger;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RegisterDoctrineLoggerPass
 *
 * Adds our query logger to the default doctrine connection.
 *
 * @package Neto\OpenmetricsBundle\DependencyInjection\Compiler
 */
class RegisterDoctrineLoggerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('doctrine.dbal.default_connection.configuration')) {
            return;
        }

        $definition = $container->getDefinition('doctrine.dbal.default_connection.configuration');
        $definition->addMethodCall('setSQLLogger', [ new Reference(DoctrineQueryMetricsLogger::class) ]);
    }
}
