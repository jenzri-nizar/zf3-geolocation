# zf3-geolocation
zf3-geolocation

zend framework 3 geolocation

##Installation

1) Ajouter l'exigence suivante à votre fichier composer.json.
Dans la section:"require"

```php
composer require jenzri-nizar/zf3-geolocation v1.0
```
2) Ouvrez votre ligne de commande et exécutez

```php
composer update
```

Le module doit être enregistré dans **config/modules.config.php**
```php
'modules' => array(
    '...',
    'Zf3\Geolocation'
),
```

##Configuration
Copiez le fichier situé dans vendor\jenzri-nizar\zf3-geolocation\config\geoip.local.php à config/geoip.local.php

Modifiez les paramètres dans le fichier config/geoip.local.php

##Exemple
```php
public function geopipAction(){
        $GeoIp=$this->GeoIp()->getInfo("87.98.187.238");
        $Weather=$this->GeoIp()->GetWeather($GeoIp->geoplugin_latitude,$GeoIp->geoplugin_longitude,$GeoIp->geoplugin_currencyCode);
        return new ViewModel(array("Weather"=>$Weather,"GeoIp"=>$GeoIp));
    }
```

geopip.phtml

```php
<?php
function cc($amount,$GeoIp) {

    if ( isset($GeoIp->geoplugin_currencyCode) && $GeoIp->geoplugin_currencyCode != 'USD' ) {
        return '(' . $GeoIp->geoplugin_currencySymbol . round( ($amount * $GeoIp->geoplugin_currencyConverter),2) . ')';
    }
    return false;
}
?>
<div class="jumbotron">
    <h1>zf3-geolocation</span></h1>

    <p>
<?php echo $Weather?>
    </p>
    <p>
        <?php echo 'Welcome to our visitors from '.$GeoIp->geoplugin_countryName;?>
    </p>
    <p>
   <?php  echo "Geolocation results for {$GeoIp->geoplugin_request}: <br />\n".
    "City: {$GeoIp->geoplugin_city} <br />\n".
    "Region: {$GeoIp->geoplugin_region} <br />\n".
    "Area Code: {$GeoIp->geoplugin_areaCode} <br />\n".
    "DMA Code: {$GeoIp->geoplugin_dmaCode} <br />\n".
    "Country Name: {$GeoIp->geoplugin_countryName} <br />\n".
    "Country Code: {$GeoIp->geoplugin_countryCode} <br />\n".
    "Longitude: {$GeoIp->geoplugin_longitude} <br />\n".
    "Latitude: {$GeoIp->geoplugin_latitude} <br />\n".
    "Currency Code: {$GeoIp->geoplugin_currencyCode} <br />\n".
    "Currency Symbol: {$GeoIp->geoplugin_currencySymbol} <br />\n".
    "Exchange Rate: {$GeoIp->geoplugin_currencyConverter} <br />\n";
    ?></p>
   <p><?php  echo '<h3>Product A costs $800  ' . cc(800,$GeoIp) . '</h3>'; ?></p>
</div>
```