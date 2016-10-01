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

    public function GetWeather ($lat,$long,$countryCode="US"){
        $Xml=new \Zend\Config\Reader\Xml();
        $data=file_get_contents("http://api.wunderground.com/auto/wui/geo/ForecastXML/index.xml?query=".$lat.",".$long);
        $dataArray= $Xml->fromString($data);
        $html ="";
        $html .= "<table cellpadding=5 cellspacing=10><tr>";
        if ($countryCode == 'US') {
            $tempScale = 'fahrenheit';
            $tempUnit = '&deg;F';
        } else {
            $tempScale = 'celsius';
            $tempUnit = '&deg;C';
        }
        foreach ($dataArray['simpleforecast']['forecastday'] as $arr) {

            $html .= "<td align='center'>" . $arr['date']['weekday'] . "<br />";
            $html .= "<img src='http://icons-pe.wxug.com/i/c/a/" . $arr['icon'] . ".gif' border=0 /><br />";
            $html .= "<font color='red'>" . $arr['high'][$tempScale] . $tempUnit . " </font>";
            $html .= "<font color='blue'>" . $arr['low'][$tempScale] . $tempUnit . "</font>";
            $html .= "</td>";


        }
        $html .= "</tr></table>";

        return $html;
    }
}