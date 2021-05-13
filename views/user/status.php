<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Status Verifikasi Administrasi Peserta</h4>
        </div>
        <?php $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']); ?>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?php echo $model->u_id; ?>">
                <?php echo $form->field($model,'u_lulus_reg')->dropDownList(['0'=>'Tidak Memenuhi Syarat','1'=>'Memenuhi Syarat','2'=>'Dipertimbangkan'],['prompt'=>'']); ?>
                <?php echo $form->field($model,'u_ket')->textArea(['rows'=>'8']); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Tutup</button>
                <button type="submit" class="btn btn-primary btn-submit"><i class="glyphicon glyphicon-save"></i> Simpan</button>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$this->registerJs("
var simpan=false;
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Loading...');
    $.ajax({
        url:'".Url::to(['status-save'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                simpan=true;
                successMsg(result.msg);
            }else{
                if(typeof result.msg=='object'){
                    $.each(result.msg,function(i,v){
                        errorMsg(v);
                    });
                }else{
                    errorMsg(result.msg);
                }
            }
            resetBtnLoading(btn,htm);
        },
        error:function(xhr,status,error){
            errorMsg(error);
            resetBtnLoading(btn,htm);
        }
    })
}).on('submit',function(e){
    e.preventDefault();
});
$('#mymodal').on('hidden.bs.modal', function (e) {
    if(simpan){
        location.reload();
    }
});
");