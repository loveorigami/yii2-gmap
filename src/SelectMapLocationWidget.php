<?php

namespace lo\widgets\gmap;

use lo\core\helpers\ArrayHelper;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class GoogleMapWidget
 * @package lo\core\widgets\gamap
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 * add to params
 * ----------------
 *  'googleMapsUrlOptions' => [
 *      'key' => 'google map key',
 *      'language' => 'ru',
 *      'version' => '3.1.18',
 *  ],
 *  'googleMapsOptions' => [
 *      'mapTypeId' => 'roadmap',
 *      'tilt' => 45,
 *      'zoom' => 10,
 *  ],
 * ------------------
 */
class SelectMapLocationWidget extends InputWidget
{
    use MapTrait;

    /**
     * @var string latitude attribute name
     */
    public $attributeLatitude;

    /**
     * @var string longitude attribute name
     */
    public $attributeLongitude;

    /**
     * @var boolean marker draggable option
     */
    public $draggable = false;

    /**
     * @var array options for attribute text input
     */
    public $textOptions = ['class' => 'form-control'];

    /**
     * @var array JavaScript options
     */
    public $jsOptions = [];

    /**
     * @var callable function for custom map render
     */
    public $renderWidgetMap;

    /**
     * Run widget
     */
    public function run()
    {
        parent::run();
        $this->registerAssets();

        // getting inputs ids
        $address = Html::getInputId($this->model, $this->attribute);
        $latitude = Html::getInputId($this->model, $this->attributeLatitude);
        $longitude = Html::getInputId($this->model, $this->attributeLongitude);

        $jsOptions = ArrayHelper::merge($this->jsOptions, [
            'address' => '#' . $address,
            'latitude' => '#' . $latitude,
            'longitude' => '#' . $longitude,
            'draggable' => $this->draggable,
        ]);

        // message about not founded address
        if (!isset($jsOptions['addressNotFound'])) {
            $hasMainCategory = isset(Yii::$app->i18n->translations['*']) || isset(Yii::$app->i18n->translations['main']);
            $jsOptions['addressNotFound'] = $hasMainCategory ? Yii::t('main', 'Address not found') : 'Address not found';
        }

        $this->view->registerJs(new JsExpression('
            $(document).ready(function() {
                $(\'#' . $this->wrapperOptions['id'] . '\').selectLocation(' . Json::encode($jsOptions) . ');
            });
        '));

        $mapHtml = Html::tag('div', '', $this->wrapperOptions);
        $mapHtml .= Html::activeHiddenInput($this->model, $this->attributeLatitude);
        $mapHtml .= Html::activeHiddenInput($this->model, $this->attributeLongitude);

        if (is_callable($this->renderWidgetMap)) {
            return call_user_func_array($this->renderWidgetMap, [$mapHtml]);
        }

        // replace custom template to use map after input=text
        if (strpos($this->field->template, '{map}') === false) {
            $this->field->template = preg_replace('/\{input\}/', '{input}{map}', $this->field->template);
        }

        $this->field->parts['{map}'] = $mapHtml;

        return Html::activeInput('text', $this->model, $this->attribute, $this->textOptions);
    }

    /**
     * Register assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        SelectMapLocationAssets::register($view);
        $view->registerJsFile($this->getGoogleMapsApiUrl(), ['position' => $view::POS_HEAD]);
    }
}