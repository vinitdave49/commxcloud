<?php

namespace Commcloud\VoiceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class CommcloudVoiceExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $container->getDefinition('commcloud_twilio_wrapper.twilio_api')
            ->addArgument($config['sid'])
            ->addArgument($config['authToken'])
            ->addArgument($config['retryattempts'])
            ->addArgument($config['version']);

        $container->getDefinition('commcloud_twilio_wrapper.twilio_capability')
            ->addArgument($config['sid'])
            ->addArgument($config['authToken']);

        $container->getDefinition('commcloud_twilio_wrapper.twilio_taskrouter')
            ->addArgument($config['sid'])
            ->addArgument($config['authToken'])
            ->addArgument($config['workspaceSid'])
            ->addArgument($config['retryattempts']);

        $container->getDefinition('commcloud_twilio_wrapper.twilio_taskrouter_worker_capability')
            ->addArgument($config['sid'])
            ->addArgument($config['authToken'])
            ->addArgument($config['workspaceSid'])
            ->addArgument($config['workerSid']);
    }
    public function getAlias()
    {
        return 'commcloud_voice';
    }
}
