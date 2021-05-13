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
        url:'".Url::to(['status-save'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                successMsg(result.msg);
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
            <div class="alert alert-warning">Ketika anda menyetujui, No. Rekam Medis pasien akan digenerate. <br>Pastikan data peserta sudah <b>VALID</b></div>
            <input type="hidden" name="update" value="<?php echo $model['ud_id']; ?>">
            <?= $form->field($model, 'ud_approve_status')->dropDownList($status) ?>
            <?= $form->field($model, 'ud_approve_ket')->textInput() ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            <button type="submit" class="btn btn-success btn-sm btn-submit"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>