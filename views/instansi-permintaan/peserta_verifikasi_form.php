<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->registerJs("
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Loading...');
    $.ajax({
        url:'".Url::to(['peserta-verify-save'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                location.reload();
            }else{
                errorMsg(result.msg);
            }
            resetBtnLoading(btn,htm);
        },
        error:function(xhr,status,error){
            resetBtnLoading(btn,htm);
            errorMsg(error);
        }
    })
}).on('submit',function(e){
    e.preventDefault();
});
var wrap_pasien_baru=$('.pasien_baru');
var wrap_isi_rm=$('.isi_rm');
$('#user-u_approve_status').change(function(e){
    var id=$(this).val();
    if(id==2){
        wrap_pasien_baru.show('slow');
    }else{
        wrap_pasien_baru.hide('slow');
        $('#user-u_is_pasien_baru').val('y');
        wrap_isi_rm.hide('slow').find('input[type=\'text\']').removeAttr('required');
    }
});
$('#user-u_is_pasien_baru').change(function(e){
    var id=$(this).val();
    if(id=='n'){
        wrap_isi_rm.show('slow').find('input[type=\'text\']').attr('required',true);
    }else{
        wrap_isi_rm.hide('slow').find('input[type=\'text\']').removeAttr('required');
    }
});
");
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Ubah Status Peserta</h4>
        </div>
        <?php $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']);  ?>
        <div class="modal-body">
            <div class="alert alert-warning">Pastikan data peserta sudah <b>VALID</b> ketika anda menyetujui status kepesertaan.<br></div>
            <input type="hidden" name="update" value="<?php echo $model['u_id']; ?>">
            <?= $form->field($model, 'u_approve_status')->dropDownList($status) ?>
            <div class="pasien_baru" style="<?php echo $model->u_approve_status!='2' ? 'display:none;' : '' ?>"><?= $form->field($model, 'u_is_pasien_baru')->dropDownList(['y'=>'Pasien Baru','n'=>'Pasien Lama']) ?></div>
            <div class="isi_rm" style="<?php echo $model->u_approve_status=='3' ? 'display:none;' : ( $model->u_is_pasien_baru!='n' ? 'display:none;' : '' ) ?>"><?= $form->field($model, 'u_rm')->textInput()->label('No. Rekam Medis Untuk Pasien Lama'); ?></div>
            <?= $form->field($model, 'u_approve_ket')->textInput() ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            <button type="submit" class="btn btn-success btn-sm btn-submit"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>