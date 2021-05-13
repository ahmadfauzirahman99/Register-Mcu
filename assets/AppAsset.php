<?php
namespace app\assets;
use yii\web\AssetBundle;
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/fontawesome/css/font-awesome.min.css',
        'plugins/toastr/toastr.min.css',
        'plugins/pace/pace.css',
        'css/site.css'
    ];
    public $js = [
        'plugins/toastr/toastr.min.js',
        'plugins/pace/pace.js',
        'plugins/inputmask/jquery.inputmask.min.js',
        'js/site.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
