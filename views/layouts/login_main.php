<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAsset;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Pendaftaran Online RSUD Arifin Achmad</title>
    <link rel="shortcut icon" href="<?php echo Url::base() ?>/img/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?php 
    if(Yii::$app->session->hasFlash('true')){
        $this->registerJs('successMsg("'.Yii::$app->session->getFlash('true').'");');
    }elseif(Yii::$app->session->hasFlash('false')){
        $this->registerJs('errorMsg("'.Yii::$app->session->getFlash('false').'");');
    }
    ?>
    <?= $content ?>
    <div class="modal fade" id="mymodal" tabindex="false" role="dialog" aria-labelledby="myModalLabel"></div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
