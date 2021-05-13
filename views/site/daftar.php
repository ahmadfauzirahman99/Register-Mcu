<?php
use yii\bootstrap\ActiveForm;
$this->title="Daftar";
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h4><strong>Silahkan Lengkapi Form Pendaftaran Berikut :</strong></h4>
            <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'u_nik')->textInput(['autofocus' => true])->hint('isi NIK tanpa spasi') ?>
                <?= $form->field($model, 'u_nama')->textInput() ?>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($model, 'u_email')->textInput() ?></div>
                    <div class="col-md-6"><?= $form->field($model, 'u_password')->passwordInput() ?></div>
                </div>
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
                ])->label(false) ?>
                
                <p>
                <button type="reset" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i> Reset</button>
                <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-save"></i> Daftar</button>
                </p>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>