<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('font_manager');
        /** @var ArrayNodeDefinition $root */
        $root = $treeBuilder->getRootNode();

        // @phpstan-ignore-next-line - Fluent interface methods are not fully recognized by PHPStan
        $root
            ->children()
                ->scalarNode('default_provider')
                    ->defaultValue('google')
                    ->info('Default font provider: google, bunny, or local')
                ->end()
                ->integerNode('cache_ttl')
                    ->defaultValue(3600)
                    ->info('API response cache TTL in seconds (default: 3600 = 1 hour)')
                ->end()
                ->booleanNode('use_locked_fonts')
                    ->defaultFalse()
                    ->info('Use locked/local fonts in production instead of CDN')
                ->end()
                ->scalarNode('fonts_dir')
                    ->defaultValue('%kernel.project_dir%/assets/fonts')
                    ->info('Directory where locked fonts are stored (served by AssetMapper)')
                ->end()
                ->scalarNode('manifest_file')
                    ->defaultValue('%kernel.project_dir%/var/font-manager.lock.json')
                    ->info('Path to the fonts manifest file')
                ->end()
                ->arrayNode('providers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('google')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('api_key')
                                    ->defaultNull()
                                    ->info('Google Fonts API key (optional, for search command)')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('bunny')
                            ->canBeEnabled()
                        ->end()
                        ->arrayNode('fontsource')
                            ->canBeEnabled()
                        ->end()
                        ->arrayNode('local')
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('directory')
                                    ->defaultValue('%kernel.project_dir%/assets/fonts/custom')
                                    ->info('Directory for custom local fonts')
                                ->end()
                                ->arrayNode('fonts')
                                    ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('display_name')->end()
                                            ->scalarNode('category')->end()
                                            ->arrayNode('weights')
                                                ->integerPrototype()->end()
                                            ->end()
                                            ->arrayNode('styles')
                                                ->scalarPrototype()->end()
                                            ->end()
                                            ->arrayNode('files')
                                                ->scalarPrototype()->end()
                                            ->end()
                                            ->scalarNode('unicode_range')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
