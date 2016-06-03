<?php

namespace elisdn\gii\fixture;

use yii\web\AssetBundle;

class GeneratorAsset extends AssetBundle
{
    public $sourcePath = '@elisdn/gii/fixture/assets';
    public $js = [
        'generator.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}