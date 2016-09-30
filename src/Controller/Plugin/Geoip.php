<?php
/**
 * Created by PhpStorm.
 * User: Jenzri
 * Date: 30/09/2016
 * Time: 21:24
 */

namespace Zf3\Geolocation\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Geoip extends AbstractPlugin
{

    private $Configs;

    private $geoip;
    /**
     * @return mixed
     */
    public function getConfigs()
    {
        return $this->Configs;
    }

    /**
     * @param mixed $Configs
     */
    public function setConfigs($Configs)
    {
        $this->Configs = $Configs;
    }


    /**
     * @return \Zf3\Geolocation\Service\Ip
     */
    public function GeoIp(){
        if(is_null($this->geoip)){
            $this->geoip=new \Zf3\Geolocation\Service\Ip($this->getConfigs());
        }

        return $this->geoip;
    }

    public function getInfo($ip=null){
        return  $this->GeoIp()->getInfo($ip);
    }
}