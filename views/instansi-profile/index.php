<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title="Edit Profil";
$this->registerjs("
	$('#".$model->formName()."').on('beforeSubmit',function(e){
        e.preventDefault();
        var form = $(this);
        var btn=$('.btn-submit');
        var htm=btn.html();
        setBtnLoading(btn,'Loading...');
        $.ajax({
            url:'".Url::to(['update'])."',
            type:'post',
            dataType:'json',
            data:form.serialize(),
            success:function(result){
                if(result.status){
                    successMsg(result.msg);
                }else{
                    errorMsg(result.msg); 
                }
                resetBtnLoading(btn,htm);
            },
            error:function(xhr,status,error){
                resetBtnLoading(btn,htm);
                errorMsg(error);     
            }
        });
    }).on('submit',function(e){
        e.preventDefault();
    });
");
?>
<h3><?php echo $this->title; ?></h3>
<p>Silahkan lengkapi data perusahaan anda : </p>
<?php $form = ActiveForm::begin(['id'=>$model->formName(),'options'=>['class'=>'well'] ]); ?>
	<?= $form->field($model, 'u_nama_depan')->textInput()->label('Nama Instansi/Perusahaan'); ?>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_alamat')->textInput()->label('Alamat Instansi/Perusahaan'); ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_nama_petugas')->textInput(); ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_no_hp')->textInput(); ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($model, 'username')->textInput()->label('Username'); ?></div>
        <div class="col-md-6"><?= $form->field($model, 'u_password')->passwordInput()->label('Password')->hint('optional, untuk ubah password'); ?></div>
    </div>
    <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-save"></i> Simpan</button>
<?php ActiveForm::end(); ?>