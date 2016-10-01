<?php
/**
 * Created by PhpStorm.
 * User: Jenzri
 * Date: 30/09/2016
 * Time: 20:55
 */

namespace Zf3\Geolocation\Service;


use Zend\Config\Reader\Xml;

class Ip
{

    public  $config = ['provider'=>NULL,'return_formats'=>NULL, 'api_key'=>NULL];

    private  $plugins         = array();

    private  $provider        = NULL;

    private  $return_formats   = NULL;

    private  $api_key         = NULL;

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @param array $plugins
     */
    public function setPlugins($plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @return null
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param null $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return null
     */
    public function getReturnFormats()
    {
        return $this->return_formats;
    }

    /**
     * @param null $return_formats
     */
    public function setReturnFormats($return_formats)
    {
        $this->return_formats = $return_formats;
    }

    /**
     * @return null
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @param null $api_key
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }


    public function __construct($config = array()) {


        $this->setPlugins(array_diff(scandir((__DIR__).'/plugins/'), array('..', '.'))) ;

        if (array_key_exists('provider',$config)) {
            $provider = $config['provider'];

            if (in_array($provider . ".php", $this->getPlugins())) {

                require (__DIR__) . '/plugins/' . $provider . '.php';
                if (array_key_exists('return_formats',$config)) {
                    $format = $config['return_formats'];

                    if(in_array($format, $plugin['accepted_formats'])){

                        $this->setReturnFormats($format);

                    } else {

                        $this->setReturnFormats($plugin['default_accepted_format']);

                    }

                }
                else{
                    $this->setReturnFormats($plugin['default_accepted_format']);
                }

                $this->setProvider($plugin) ;

                $this->setApiKey(array_key_exists('api_key',$config) ? $config['api_key']:null);

            }
            else{
                throw new \Exception(404, 'The requested Item could not be found.');
            }
        }
        else{
            require (__DIR__) . '/plugins/geoplugin.php';
            $this->setProvider($plugin);
            $this->setReturnFormats($plugin['default_accepted_format']);

        }
    }

    /**
     * @param $ip
     * @return String
     */
    private  function createUrl($ip){
        $urlTmp = preg_replace('!\{\{(accepted_formats)\}\}!', $this->getReturnFormats(), ($this->getProvider())['plugin_url']);
        $urlTmp = preg_replace('!\{\{(ip)\}\}!', $ip, $urlTmp);

        if($this->getApiKey())
            $urlTmp = preg_replace('!\{\{(api_key)\}\}!', $this->getApiKey(), $urlTmp);

        return $urlTmp;
    }


    /**
     * @param null $ip
     * @return object|Xml|string
     */
    public  function getInfo($ip=NULL){

        if(!isset($ip))
            $ip = $this->getIP();

        $url = $this->createUrl($ip);

       // print_r($url); exit;
        $datar=file_get_contents($url);
        switch ($this->getReturnFormats()){
            case "php":$datar=unserialize($datar);break;
            case "json":$datar=\Zend\Json\Decoder::decode($datar);break;
            case "xml":$xml=new Xml();
                $datar=$xml->fromString($datar);
                ;break;
        }

        return $datar;
    }

    /**
     * @return string
     */
    private  function getIP(){
        $ip = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                    getenv('HTTP_FORWARDED_FOR')?:
                        getenv('HTTP_FORWARDED')?:
                            getenv('REMOTE_ADDR');
        return $ip;
    }

    /**
     * @param null $provider
     * @param null $format
     * @param null $api_key
     */
    public  function getPlugin($provider=NULL, $format=NULL, $api_key=NULL){

        $this->setPlugins(array_diff(scandir((__DIR__).'/plugins/'), array('..', '.'))) ;

        if(isset($api_key)){
            $this->setApiKey($api_key) ;
        }


        if (in_array($provider . ".php", $this->getPlugins())) {
            require (__DIR__) . '/plugins/' . $provider . '.php';
            if(in_array($format, $plugin['accepted_formats'])){
                $this->setReturnFormats($format);
            } else {
                $this->setReturnFormats( $plugin['default_accepted_format']);
            }
            $this->setProvider($plugin);
        }
    }
}