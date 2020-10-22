<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle;

use Neto\OpenmetricsBundle\DependencyInjection\Compiler\ResolveAdapterDefinitionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NetoOpenmetricsBundle extends Bundle
{
    public function build(ContainerBuilder $containerBuilder)
    {
        parent::build($containerBuilder);

        $containerBuilder->addCompilerPass(new ResolveAdapterDefinitionPass());
    }
}