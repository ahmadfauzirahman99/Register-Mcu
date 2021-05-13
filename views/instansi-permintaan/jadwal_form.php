<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
$this->registerJs("
var simpan=false;
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'".url::to(['jadwal-save'])."',
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
$('#mymodal').on('hidden.bs.modal',function(e){
    if(simpan){
        location.reload();
    }
});
$('input[name=\'UserPermintaanJadwal[auto_set_jadwal]\']').click(function(e){
    var id=$(this).val();
    var jdwl=$('.tgl_jadwal');
    if(id==2){
        jdwl.show('slow').find('input[type=\'text\']').attr('required',true);
    }else{
        jdwl.hide('slow').find('input[type=\'text\']').removeAttr('required');
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
            <?php
            if(!$model->isNewRecord){
                ?><input type="hidden" name="update" value="<?php echo $model->upj_id; ?>"><?php
            }
            ?>
            <?= $form->field($model, 'upj_up_id',['template'=>'{input}','options'=>['tag'=>false],])->hiddenInput()->label(false); ?>
            <?= $form->field($model, 'upj_kuota')->textInput(['maxlength' => true]) ?>
            <?php 
            if($model->isNewRecord){
                echo $form->field($model, 'auto_set_jadwal')->radioList([1=>'Otomatis',2=>'Manual']);
            }
            ?>
            <?= $form->field($model, 'upj_tgl',['options'=>['class'=>'tgl_jadwal','style'=>$model->isNewRecord ? 'display:none;' : '']])->widget(DatePicker::className(),[
                'dateFormat' => 'dd-MM-yyyy',
                'options'=>['class'=>'form-control','autocomplete'=>'off','readonly'=>'readonly'],
                'clientOptions'=>[
                    'changeMonth'=>true,
                    'changeYear'=>true,
                ]
            ]) ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-paper-plane"></i> Simpan</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>