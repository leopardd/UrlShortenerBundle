<?php

namespace Leopardd\Bundle\UrlShortenerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class LeoparddUrlShortenerExtension extends Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);

		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('services.yml');

		// Hashids Config
		if (isset($config['hashids'])) {
			if (isset($config['hashids']['salt'])) {
				$container->setParameter('leopardd_url_shortener.hashids.salt', $config['hashids']['salt']);
			}

			if (isset($config['hashids']['min_length'])) {
				$container->setParameter('leopardd_url_shortener.hashids.min_length', $config['hashids']['min_length']);
			}

			if (isset($config['hashids']['alphabet'])) {
				$container->setParameter('leopardd_url_shortener.hashids.alphabet', $config['hashids']['alphabet']);
			}
		}
	}
}