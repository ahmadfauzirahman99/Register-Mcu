<?php
use app\widgets\App;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h4><strong><?php echo $this->title; ?></strong></h4>
    <p>Silahkan isi form berikut untuk login:</p>
    <div class="row">
        <div class="col-md-4">
            <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'nik')->textInput(['autofocus' => true]) ?>
                <?php echo $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::classname(), [
                    'captchaAction' => '/site/captcha',
                    'options'=>[
                        'autocomplete'=>'off',
                        'class'=>'form-control',
                        'placeholder'=>'Ketik Captcha',
                        'style'=>'padding-left:10px; padding-right:10px;'
                    ],
                    'imageOptions'=>[
                        'foreColor'=>'red'
                    ],
                ]) ?>
                <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-log-in"></i> Login</button>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
// echo Yii::$app->getSecurity()->generatePasswordHash('12345');