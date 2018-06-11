<?php

namespace lo\widgets\gmap;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * GoogleMaps displays a set of user addresses as markers on the map.
 * To use GoogleMaps, you need to configure its [[data]] property. For example:
 *
 * ```php
 * echo MarkersWidget::widget([
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
class MarkersWidget extends Widget
{
    use MapTrait;

    /**
     * @var array data locations
     */
    public $data = [];

    /**
     * Init widget
     */
    public function init()
    {
        parent::init();
        if (is_array($this->data) === false) {
            throw new InvalidConfigException('The "data" property must be of the type array');
        }
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        if (empty($this->data) && $this->renderEmptyMap === false) {
            return;
        }

        echo Html::beginTag('div', [
            'id' => $this->getId(),
            'style' => $this->wrapperOptions['style']
        ]);
        echo Html::tag('div', '', [
            'id' => $this->wrapperOptions['id'],
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
        MarkersAsset::register($view);
        Gmap3Asset::register($view);

        $view->registerJsFile($this->getGoogleMapsApiUrl(), ['position' => $view::POS_HEAD]);

        $id = $this->wrapperOptions['id'];
        $options = Json::encode($this->getGoogleMapsOptions());
        $data = Json::encode($this->data);

        /**
         * https://github.com/jbdemonte/gmap3/issues/123
         * https://github.com/jbdemonte/gmap3/issues/108
         */
        $view->registerJs("multiMarker($id, $options, $data);", $view::POS_END);
    }
}