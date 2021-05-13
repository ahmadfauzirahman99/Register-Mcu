<?php
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
?>
<div class="well">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'JENIS_KEL')->dropDownList(['L'=>'Laki-laki','P'=>'Perempuan'],['prompt'=>'']); ?></div>
        <div class="col-md-4"><?= $form->field($model, 'KD_AGAMA')->dropDownList(ArrayHelper::map($agama,'Kode','Agama'),['prompt'=>'']); ?></div>
        <div class="col-md-4"><?= $form->field($model, 'NAMAAYAH')->textInput(); ?></div>
        <div class="col-md-4"><?= $form->field($model, 'NAMAIBU')->textInput(); ?></div>
    </div>
    <div class="form-group">
        <button type="reset" class="btn btn-default btn-sm">Reset</button>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
