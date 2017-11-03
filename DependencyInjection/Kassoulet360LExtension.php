<?php

namespace Kassoulet\360LBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;


/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */

/**
 * Class Kassoulet360LExtension
 *
 * @package Kassoulet\360LBundle\DependencyInjection
 */
class Kassoulet360LExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        //chargement des services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');        
        
        //Définition du service API
        $apiServiceDefinition = new Definition('Postmark\PostmarkClient', array($config['api_key']) );        
        $container->addDefinitions(array(
            'kassoulet.swift_transport.postmark.api' => $apiServiceDefinition
        ));
        
        //Alias utilisé dans le fichier parameters, repris dans le fichier config
        $container->setAlias('kassoulet_postmark', 'kassoulet.swift_transport.postmark');
        
    }
    
    /**
     * Sets up configuration for the extension
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function setupConfiguration(array $configs, ContainerBuilder $container)
    {
    	$processor = new Processor();
    	$configuration = new Configuration();
    	$config = $processor->processConfiguration($configuration, $configs);
    
    	$container->setParameter("Kassoulet.360L.company_id", $config["company_id"]);
    	$container->setParameter("Kassoulet.360L.api_key", $config["api_key"]);    	
    	
    }
    
    /**
     * @return string
     */    
    public function getAlias()
    {
    	return "kassoulet_360L";
    }
}
