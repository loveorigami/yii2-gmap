<?php

namespace lo\widgets\gmap;

use yii\web\AssetBundle;

/**
 * Class GoogleMapsAsset
 * @package modules\base\widgets\gmap
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class GoogleMapsAsset extends AssetBundle
{

    /**
     * @var array
     */
    public $js = [
        'markerclusterer_compiled.js',
        'googlemap.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . "/assets";
        parent::init();
    }
}