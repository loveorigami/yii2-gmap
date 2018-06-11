<?php
namespace lo\widgets\gmap;

use yii\web\AssetBundle;

/**
 * Class SelectMapLocationAssets
 * @package lo\widgets\gmap
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class SelectMapLocationAssets extends AssetBundle
{
    public $js = [
        'select-google-map-location.js',
    ];

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
