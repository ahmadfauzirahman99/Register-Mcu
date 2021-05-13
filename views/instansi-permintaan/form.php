<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\web\JsExpression;
$this->registerJs("
var simpan=false;
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'".url::to(['save'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                simpan=true;
                successMsg(result.msg);
            }else{
                errorMsg(result.msg);
            }
            resetBtnLoading(btn,htm);
        },
        error:function(xhr,status,error){
            errorMsg(error);
            resetBtnLoading(btn,htm);
        }
    });
}).on('submit',function(e){
    e.preventDefault();
});
$('#mymodal').on('hidden.bs.modal', function (e) {
    if(simpan){
        $.pjax.reload({container: '#pjax-permintaan', async: false});
    }
});
var enableDate=".json_encode($disable_tgl).";
");
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Form <?php echo $model->isNewRecord ? 'Tambah' : 'Edit' ?> Permintaan Pemeriksaan</h4>
        </div>
        <?php $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']); ?>
        <div class="modal-body">
            <?php
            if(!$model->isNewRecord){
                ?><input type="hidden" name="update" value="<?php echo $model->up_id; ?>"><?php
            }
            ?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'up_nama')->textInput(['maxlength' => true]) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'up_total_peserta')->textInput(['maxlength' => true]) ?></div>
            </div>
            <?= $form->field($model, 'up_jenis_mcu_id')->dropDownList(ArrayHelper::map($jenis_mcu,'jm_id','jm_nama'),['prompt'=>'']) ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'up_tgl_mulai')->widget(DatePicker::className(),[
                        'dateFormat' => 'dd-MM-yyyy',
                        'options'=>['class'=>'form-control','autocomplete'=>'off','readonly'=>'readonly'],
                        'clientOptions'=>[
                            'minDate'=>date('d-m-Y'),
                            'changeMonth'=>true,
                            'changeYear'=>true,
                            'beforeShowDay'=>new JsExpression('disabledDate')
                        ]
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'up_tgl_selesai')->widget(DatePicker::className(),[
                        'dateFormat' => 'dd-MM-yyyy',
                        'options'=>['class'=>'form-control','autocomplete'=>'off','readonly'=>'readonly'],
                        'clientOptions'=>[
                            'minDate'=>date('d-m-Y'),
                            'changeMonth'=>true,
                            'changeYear'=>true,
                            'beforeShowDay'=>new JsExpression('disabledDate')
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-paper-plane"></i> Simpan</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>