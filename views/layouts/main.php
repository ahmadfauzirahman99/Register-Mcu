<?php
use app\widgets\App;
use app\widgets\Alert;
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
    <title><?= Html::encode($this->title) ?> - Pendaftaran MCU RSUD Arifin Achmad</title>
    <link rel="shortcut icon" href="<?php echo Url::base() ?>/img/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        echo $this->render('menu');
        ?>
        <div class="container">
            <?php 
            if(Yii::$app->session->hasFlash('true')){
                $this->registerJs('successMsg("'.Yii::$app->session->getFlash('true').'");');
            }elseif(Yii::$app->session->hasFlash('false')){
                $this->registerJs('errorMsg("'.Yii::$app->session->getFlash('false').'");');
            }
			if(App::isPeserta()){
				//echo $this->render('timeline');
			}
            echo $content;
        ?>
        </div>
    </div>
    <div class="modal fade" id="mymodal" tabindex="false" role="dialog" aria-labelledby="myModalLabel"></div>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; RSUD ARIFIN ACHMAD <?= date('Y') ?></p>
        </div>
    </footer>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
