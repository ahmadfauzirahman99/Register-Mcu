<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
/*$this->registerJs("
$('#usersearch-u_formasi_pendidikan_id').change(function(e){
    e.preventDefault();
    var id=$(this).val();
    var el=$('#usersearch-u_formasi_id');
    if(id){
        var btn=$('.btn-submit');
        $.ajax({
            url:'".Url::to(['get-formasi'])."',
            type:'post',
            dataType:'json',
            data:{id:id},
            success:function(result){
                if(result.data.length>0){
                    var htm='<option value=\'\'>- pilih formasi -</option>';
                    $.each(result.data,function(i,v){
                        htm+='<option value=\''+v.id+'\'>'+v.nama+'</option>';
                    });
                    el.html(htm);
                }
            },
            error:function(xhr,status,error){
                console.log(error);
            }
        });
    }else{
        el.empty();
    }
});
");*/
?>
<div class="user-search">
    <?php $form = ActiveForm::begin([
        'action' => [Yii::$app->controller->action->id],
        'method' => 'get',
        'options' => [
            'data-pjax' => 0
        ],
    ]); ?>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_nik') ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_nama') ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_email') ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_alamat') ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_jkel')->dropDownList(['l'=>'Laki-laki','p'=>'Perempuan'],['prompt'=>'']) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_tgl_lahir')->widget(DatePicker::className(),[
                    'dateFormat' => 'dd-MM-yyyy',
                    'options'=>['class'=>'form-control','autocomplete'=>'off'],
                    'clientOptions'=>[
                        'maxDate'=>date('d-m-Y'),
                        'changeMonth'=>true,
                        'changeYear'=>true,
                    ]
        ]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_tmpt_lahir') ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_no_hp') ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_jenis_pendidikan_id')->dropDownList(ArrayHelper::map($pendidikan,'jp_id','jp_nama'),['prompt'=>'']) ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_jurusan') ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_instansi') ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_ipk') ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_formasi_pendidikan_id')->dropDownList(ArrayHelper::map($pendidikan,'jp_id','jp_nama'),['prompt'=>'']) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_formasi_id')->dropDownList(ArrayHelper::map($formasi,'id','nama'),['prompt'=>'']); ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_jalur_perawat')->dropDownList(['p'=>'Prestasi','s'=>'Seleksi'],['prompt'=>'']) ?></div>
    </div>
	<?= $form->field($model, 'u_ket'); ?>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
