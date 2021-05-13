<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use yii\web\JsExpression;
$this->registerJs("
var simpan=false;
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'".url::to(['status-save'])."',
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
        location.reload();
    }
});
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
            <input type="hidden" name="id" value="<?php echo $model->up_id; ?>">
            <?= $form->field($model, 'up_status')->dropDownList(['0'=>'Belum Disetujui','1'=>'Sudah Disetujui']) ?>
            
            <?php echo $form->field($model,'up_paket_id')->widget(Select2::className(),[
                'data'=>ArrayHelper::map($paket,'kode','nama'),
                'options' => ['placeholder' => 'ketik nama paket','class'=>'form-control paket','multiple'=>true],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]);
            ?>
            <?= $form->field($model, 'up_debitur_id')->dropDownList(ArrayHelper::map($debitur,'d_kode','d_nama')) ?>
            <?= $form->field($model, 'up_status_ket')->textArea() ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-paper-plane"></i> Simpan</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>