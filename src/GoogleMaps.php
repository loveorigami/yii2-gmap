<?php

namespace lo\widgets\gmap;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * GoogleMaps displays a set of user addresses as markers on the map.
 *
 * To use GoogleMaps, you need to configure its [[data]] property. For example:
 *
 * ```php
 * echo yii2mod\google\maps\markers\GoogleMaps::widget([
 *     'data' => [
 *           [
 *              'position' => [$model->lat, $model->lng],
 *              'open' => $model->id == $this->objectId,
 *              'content' => $model->name,
 *           ],
 *     ]
 * ]);
 * ```
 */
class GoogleMaps extends Widget
{
    /**
     * @var array data locations
     */
    public $data = [];

    /**
     * @var string main wrapper height
     */
    public $wrapperHeight = '500px';

    /**
     * @var string google maps url
     */
    public $googleMapsUrl = 'https://maps.googleapis.com/maps/api/js?';

    /**
     * libraries - Example: geometry, places. Default - empty string
     * version - 3.exp (Default)
     * @var array google maps url options(v, language, key, libraries)
     */
    public $googleMapsUrlOptions = [];

    /**
     * Google Maps options (mapTypeId, tilt, zoom, etc...)
     * @see https://developers.google.com/maps/documentation/javascript/reference
     * @var array
     */
    public $googleMapsOptions = [];

    /**
     * @see https://developers.google.com/maps/documentation/javascript/reference#InfoWindowOptions
     * @var array
     */
    public $infoWindowOptions = [];

    /**
     * @var string google maps container id
     */
    public $containerId = 'map_canvas';

    /**
     * @var bool render empty map, if data is empty. Defaults to 'true'
     */
    public $renderEmptyMap = true;


    /**
     * Init widget
     */
    public function init()
    {
        parent::init();
        if (is_array($this->data) === false) {
            throw new InvalidConfigException('The "data" property must be of the type array');
        }
        $this->googleMapsOptions = $this->getGoogleMapsOptions();
        $this->googleMapsUrlOptions = $this->getGoogleMapsUrlOptions();
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        if (empty($this->data) && $this->renderEmptyMap === false) {
            return;
        }

        echo Html::beginTag('div', ['id' => $this->getId(), 'style' => "height: {$this->wrapperHeight}"]);
        echo Html::tag('div', '', [
            'id' => $this->containerId,
            'class' => 'gmap3'
        ]);
        echo Html::endTag('div');
        $this->registerAssets();
        parent::run();
    }

    /**
     * Register assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        GoogleMapsAsset::register($view);
        Gmap3Asset::register($view);
        $view->registerJsFile($this->getGoogleMapsApiUrl(), ['position' => View::POS_HEAD]);

        $id = $this->containerId;
        $options = Json::encode($this->googleMapsOptions);
        $data = Json::encode($this->data);

        /**
         * https://github.com/jbdemonte/gmap3/issues/123
         * https://github.com/jbdemonte/gmap3/issues/108
         */

        $view->registerJs("multiMarker($id, $options, $data);", $view::POS_END);

    }

    /**
     * Get google maps api url
     * @return string
     */
    protected function getGoogleMapsApiUrl()
    {
        return $this->googleMapsUrl . http_build_query($this->googleMapsUrlOptions);
    }

    /**
     * Get google maps url options
     * @return array
     */
    protected function getGoogleMapsUrlOptions()
    {
        if (isset(Yii::$app->params['googleMapsUrlOptions']) && empty($this->googleMapsUrlOptions)) {
            $this->googleMapsUrlOptions = Yii::$app->params['googleMapsUrlOptions'];
        }
        return ArrayHelper::merge($this->googleMapsUrlOptions, array_filter([
            'key' => null,
            'libraries' => null,
        ]));
    }

    /**
     * Get google maps options
     * @return array
     */
    protected function getGoogleMapsOptions()
    {
        if (isset(Yii::$app->params['googleMapsOptions'])) {
            $googleMapsOptions = Yii::$app->params['googleMapsOptions'];
        } else {
            $googleMapsOptions = [];
        }

        return ArrayHelper::merge($googleMapsOptions, [
            'mapTypeId' => 'roadmap',
            'center' => [46.578498, 2.457275],
            'zoom' => 15,
        ], $this->googleMapsOptions);
    }
}