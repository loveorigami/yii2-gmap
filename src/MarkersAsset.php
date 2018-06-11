<?php

namespace lo\widgets\gmap;

use yii\web\AssetBundle;

/**
 * Class MarkersAsset
 * @package lo\widgets\gmap
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class MarkersAsset extends AssetBundle
{
    /**
     * @var array
     */
    public $js = [
        'gmap3.js',
    ];

    /**
     * @var array
     */
    public $css = [
        'gmap3.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'lo\widgets\gmap\Gmap3Asset',
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