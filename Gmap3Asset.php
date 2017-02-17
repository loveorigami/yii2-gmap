<?php

namespace lo\widgets\gmap;

use yii\web\AssetBundle;

/**
 * Class Gmap3Asset
 * @package modules\base\widgets\gmap
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Gmap3Asset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = "@bower/gmap3/dist";

    /**
     * @var array
     */
    public $js = [
        'gmap3.min.js'
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}