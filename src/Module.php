<?php

namespace Zf3\Geolocation;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
class Module implements ConfigProviderInterface{
    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sharedManager = $e->getApplication()->getEventManager()->getSharedManager();
        $sm            = $e->getApplication()->getServiceManager();
        $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
            function($e) use ($sm) {
                $config=$sm->get("Configuration");
                $geoipConfig = array_key_exists("geoip",$config) ? $config['geoip']:array();
                $sm->get('ControllerPluginManager')->get('GeoIp')->setConfigs($geoipConfig);
            },2
        );
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getControllerPluginConfig() {
        return array(
            'invokables' => array(
                'GeoIp' =>Controller\Plugin\Geoip::class,
            )
        );
    }
}
