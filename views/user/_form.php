<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'u_nik')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'u_nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'u_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'u_alamat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'u_jkel')->dropDownList([ 'p' => 'P', 'l' => 'L', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'u_tgl_lahir')->textInput() ?>

    <?= $form->field($model, 'u_tmpt_lahir')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'u_no_hp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'u_jenis_pendidikan_id')->textInput() ?>

    <?= $form->field($model, 'u_instansi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'u_ipk')->textInput() ?>

    <?= $form->field($model, 'u_formasi_pendidikan_id')->textInput() ?>

    <?= $form->field($model, 'u_formasi_id')->textInput() ?>

    <?= $form->field($model, 'u_jalur_perawat')->dropDownList([ 'p' => 'P', 's' => 'S', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'u_password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'u_status')->dropDownList([ '0', '1', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'u_level')->dropDownList([ 1 => '1', 2 => '2', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'u_auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'u_last_login')->textInput() ?>

    <?= $form->field($model, 'u_updated_at')->textInput() ?>

    <?= $form->field($model, 'u_created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
