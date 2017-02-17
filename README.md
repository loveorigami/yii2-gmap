Google Maps Markers Widget for Yii2
===================================

GoogleMaps Widget displays a set of user addresses as markers on the map.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require loveorigami/yii2-gmap-markers "*"
```

or add

```
"loveorigami/yii2-gmap-markers": "*"
```

to the require section of your composer.json.

Usage
-----

To use GoogleMaps, you need to configure its [[locations]] property. For example:

```php
echo lo\widgets\gmap\GoogleMaps::widget([
		[
			'position' => [$model->lat, $model->lng],
			'open' => true,
			'content' => $model->name,
		],
		[
			'position' => [45.143400, -5.372400],
			'content' => 'My Marker',
		]
]);
```

Configuration
-------------

To configure the Google Maps key or other options like language, version, library, or map options:

```php
echo lo\widgets\gmap\GoogleMaps::widget([
    'locations' => [...],
    'googleMapsUrlOptions' => [
        'key' => 'this_is_my_key',
        'language' => 'id',
        'version' => '3.1.18',
    ],
    'googleMapsOptions' => [
        'mapTypeId' => 'roadmap',
        'tilt' => 45,
        'zoom' => 5,
    ],
]);
```

OR via yii params configuration. For example:

```php
'params' => [
    'googleMapsUrlOptions' => [
        'key' => 'this_is_my_key',
        'language' => 'id',
        'version' => '3.1.18',
     ],
    'googleMapsOptions' => [
        'mapTypeId' => 'roadmap',
        'tilt' => 45,
        'zoom' => 10,
    ],
],
```

To get key, please visit [page](https://developers.google.com/maps/documentation/javascript/get-api-key)

Google Maps Options
-------------------

You can find them on the [options page](https://developers.google.com/maps/documentation/javascript/reference)

#### Example
------------

