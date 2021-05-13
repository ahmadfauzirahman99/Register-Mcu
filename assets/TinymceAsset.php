<?php
namespace app\assets;
use yii\web\AssetBundle;
class TinymceAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'plugins/tinymce/tinymce.min.js'
    ];
    public $depends = [
        'app\assets\AppAsset'
    ];
}
