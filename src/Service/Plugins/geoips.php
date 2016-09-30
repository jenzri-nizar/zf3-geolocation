<?php
/**
 * Created by PhpStorm.
 * User: Jenzri
 * Date: 30/09/2016
 * Time: 21:45
 */

$plugin = [
    'plugin_url'                => 'http://api.geoips.com/key/{{api_key}}/output/{{accepted_formats}}/timezone/true/hostname/true/language/true/currency/true/ip/{{ip}}',
    'accepted_formats'          => ['json', 'xml'],
    'default_accepted_format'   => 'json',
];