<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
$this->registerJs("
var simpan=false;
$('#user-u_tgl_lahir').inputmask({
    mask:'99-99-9999'
});
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    var form=$(this);
    var formData = new FormData(form[0]);
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'".url::to(['peserta-save'])."',
        type:'post',
        dataType:'json',
        data:formData,
        contentType: false,
        cache: false,
        processData:false,
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
        $.pjax.reload({container: '#pjax-peserta', async: false});
    }
});
$('#user-u_paket_id').change(function(e){
    var id =$(this).val();
    if(id){
        $.post('".Url::to(['paket-detail'])."',{id:id},function(result){
            $('.list-tindakan').html(result);
        });
    }else{
        $('.list-tindakan').html('');
    }
});
");
?>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Form <?php echo $model->isNewRecord ? 'Tambah' : 'Edit' ?> Peserta</h4>
        </div>
        <?php $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']); ?>
        <div class="modal-body">
            <?php
            if(!$model->isNewRecord){
                ?><input type="hidden" name="update" value="<?php echo $model->u_id; ?>"><?php
            }
            ?>
            <?php //$form->field($model, 'u_up_id',['template'=>'{input}','options'=>['tag'=>false],])->hiddenInput()->label(false); ?>
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-6"><?= $form->field($model, 'u_nik')->textInput(['maxlength' => true]) ?></div>
                        <div class="col-md-6"><?= $form->field($model, 'u_no_peserta')->textInput(['maxlength' => true]) ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><?= $form->field($model, 'u_nama_depan')->textInput(['maxlength' => true]) ?></div>
                        <div class="col-md-6"><?= $form->field($model, 'u_jkel')->dropDownList(['L'=>'Laki-laki','P'=>'Perempuan']) ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><?= $form->field($model, 'u_tmpt_lahir')->textInput(['maxlength' => true]) ?></div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'u_tgl_lahir')->textInput()->hint('format : tanggal-bulan-tahun') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><?= $form->field($model, 'u_pekerjaan')->dropDownList(ArrayHelper::map($pekerjaan,'Nomor','PerkerjaanJabatan')) ?></div>
                        <div class="col-md-6"><?= $form->field($model, 'u_jabatan_pekerjaan')->textInput(['maxlength' => true])->label('Pangkat dan Jabatan') ?></div>
                    </div>
                    <div class="row">
                        <?php
                        if($permintaan->up_jenis_mcu_id==1){
                            ?>
                            <div class="col-md-8"><?php echo $form->field($model, 'u_tempat_tugas')->textInput(['maxlength' => true]); ?></div>
                            <?php
                        }
                        ?>
                        <div class="col-md-<?php echo $permintaan->up_jenis_mcu_id==1 ? '4' : '12' ?>"><?= $form->field($model, 'u_upj_id')->dropDownList(ArrayHelper::map($jadwalperiksa,'id','tgl')) ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8"><?= $form->field($model,'ktp')->fileInput();  ?></div>
                        <div class="col-md-4">
                            <?php 
                            if($model->u_ktp!=NULL){
                                ?>
                                <a href="<?php echo Url::to(['get-ktp','id'=>$model->u_id]); ?>" class="btn btn-info btn-block" title="klik untuk lihat berkas" target="_blank" style="margin-top:20px;"><i class="fa fa-eye"></i> Lihat Berkas
                                    <!-- <img src="<?php echo Url::to(['get-ktp','id'=>$model->u_id]); ?>" width="100%"> -->
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'u_paket_id')->dropDownList(ArrayHelper::map($paket,'kode','nama'),['prompt'=>'']) ?>
                    <div class="list-tindakan" style="max-height:400px; overflow-x:hidden; overflow-y:auto;"></div>
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