<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 11.06.2018
 * Time: 11:46
 */

namespace lo\widgets\gmap;

use Yii;
use yii\helpers\ArrayHelper;

trait MapTrait
{
    /**
     * @var array wrapper map options
     */
    public $wrapperOptions = [];

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
     * @var bool render empty map, if data is empty. Defaults to 'true'
     */
    public $renderEmptyMap = true;

    /**
     * Wrapper options
     */
    protected function getWrapperOptions(){
        if (!isset($this->wrapperOptions['id'])) {
            $this->wrapperOptions['id'] = $this->id;
        }
        if (!isset($this->wrapperOptions['style'])) {
            $this->wrapperOptions['style'] = 'width: 100%; height: 500px;';
        }
    }

    /**
     * Get google maps api url
     * @return string
     */
    protected function getGoogleMapsApiUrl()
    {
        $this->getGoogleMapsUrlOptions();
        return $this->googleMapsUrl . http_build_query($this->getGoogleMapsUrlOptions());
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
            'libraries' => 'places',
            'sensor' => true,
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