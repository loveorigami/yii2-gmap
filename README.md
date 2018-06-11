Google Maps Widgets for Yii2
===================================

GoogleMaps Widget displays a set of user addresses as markers on the map.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require loveorigami/yii2-gmap "*"
```

or add

```
"loveorigami/yii2-gmap": "*"
```

to the require section of your composer.json.

Configuration
-------------

To configure the Google Maps key or other options like language, version, library, or map options:

```php
echo lo\widgets\gmap\MarkersWidget::widget([
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

Google Maps Options you can find them on the [options page](https://developers.google.com/maps/documentation/javascript/reference)

Widgets
-------------------

* Markers Widget
----

To use GoogleMaps, you need to configure its [[locations]] property. For example:

```php
echo lo\widgets\gmap\MarkersWidget::widget([
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

* Select Map Location Widget
----

Declare model class which will save geographic coordinates:

```php
class SearchLocation extends \yii\base\Model
{
    ...
    public $address;
    public $longitude;
    public $latitude;
    ...
}
```

Render widget:
```php
$model = new SearchLocation();
$form = \yii\widgets\ActiveForm::begin();
...
$form->field($model, 'address')->widget(\lo\widgets\gmap\SelectMapLocationWidget::class, [
    'attributeLatitude' => 'latitude',
    'attributeLongitude' => 'longitude',
]);
...
\yii\widgets\ActiveForm::end();
```

To use movable marker on the map describe draggable option:
```php
$model = new SearchLocation();
$form = \yii\widgets\ActiveForm::begin();
...
$form->field($model, 'address')->widget(\lo\widgets\gmap\SelectMapLocationWidget::className(), [
    'attributeLatitude' => 'latitude',
    'attributeLongitude' => 'longitude',
    'draggable' => true,
]);
...
\yii\widgets\ActiveForm::end();
```

To use custom field template use placeholder {map} for ActiveField:
```php
$model = new SearchLocation();
$form = \yii\widgets\ActiveForm::begin();
...
$form->field($model, 'address', [
    'template' => '{label}<div class="custom-class"><div class="form-control">{input}</div>{map}</div>{error}',
])->widget(\lo\widgets\gmap\SelectMapLocationWidget::className(), [
    'attributeLatitude' => 'latitude',
    'attributeLongitude' => 'longitude',
]);
...
\yii\widgets\ActiveForm::end();
```

