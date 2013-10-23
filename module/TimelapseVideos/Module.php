<?php
/**
 * @package     TimelapseVideos
 * @author      Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date        October 22, 2013
 */
namespace TimelapseVideos;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
