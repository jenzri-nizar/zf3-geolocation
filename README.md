# zf3-geolocation
zf3-geolocation

zend framework 3 geolocation

##Installation

1) Ajouter l'exigence suivante à votre fichier composer.json.
Dans la section:"require"

```php
"composer require jenzri-nizar/zf3-geolocation": "*"
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
Modifiez les paramètres dans le fichier config/geoip.local.php