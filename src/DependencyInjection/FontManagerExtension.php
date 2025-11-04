<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class FontManagerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // General parameters
        $container->setParameter('font_manager.default_provider', $config['default_provider'] ?? 'google');
        $container->setParameter('font_manager.cache_ttl', $config['cache_ttl'] ?? 3600);
        $container->setParameter('font_manager.use_locked_fonts', $config['use_locked_fonts'] ?? false);
        $container->setParameter('font_manager.fonts_dir', $config['fonts_dir'] ?? '%kernel.project_dir%/assets/fonts');
        $container->setParameter(
            'font_manager.manifest_file',
            $config['manifest_file'] ?? '%kernel.project_dir%/var/font-manager.lock.json'
        );

        // Provider configurations
        $container->setParameter('font_manager.providers', $config['providers'] ?? []);
        $container->setParameter('font_manager.providers.google', $config['providers']['google'] ?? ['enabled' => true]);
        $container->setParameter('font_manager.providers.bunny', $config['providers']['bunny'] ?? ['enabled' => true]);
        $container->setParameter('font_manager.providers.fontsource', $config['providers']['fontsource'] ?? ['enabled' => true]);
        $container->setParameter('font_manager.providers.local', $config['providers']['local'] ?? ['enabled' => false]);
    }

    public function getAlias(): string
    {
        return 'font_manager';
    }
}
