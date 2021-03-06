<?php

namespace Biliboo\ChartBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of SerieCompilerPass
 *
 * @author Pierre Devillard <dp@biliboo.org>
 */
class SerieCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('biliboo_chart.series.resolver')) {
            return;
        }

        $definition = $container->getDefinition('biliboo_chart.series.resolver');

        $taggedServices = $container->findTaggedServiceIds(
            'chart.series'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall( 'addSerie', [
                    new Reference($id), $attributes["alias"]
                ]);
            }
        }
    }
}
