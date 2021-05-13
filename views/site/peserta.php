<?php
use app\widgets\App;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\captcha\Captcha;
$this->title = 'Informasi Peserta';
$this->registerJs("
$('#user-u_formasi_pendidikan_id').change(function(e){
    e.preventDefault();
    var id=$(this).val();
    var el=$('#user-u_formasi_id');
    if(id){
        if(id!=3){
            $('.jalur-perawat').hide('slow');
        }
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
$('#user-u_anggota_darurat').change(function(e){
    var id=$(this).val();
    var f=$('#user-u_anggota_darurat_ket');
    if(id==1){
        f.removeAttr('disabled').attr('required',true);
    }else{
        f.val('');
        f.removeAttr('required').attr('disabled',true);
    }
});
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'".Url::to(['peserta-save'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                successMsg(result.msg);
                $('.btn-next').show('hide');
            }else{
                $.each(result.msg,function(i,v){
                    errorMsg(v);
                });
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
");
$this->registerCss("
input[type='text']{
    text-transform: uppercase;
}
");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4><strong>Silahkan lengkapi form berikut :</strong></h4>
            <h4>Biodata</h4>
            <?php $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']); ?>
            <div class="row">
                <div class="col-md-3"><?= $form->field($model, 'u_nik')->textInput(['disabled'=>true]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_email')->textInput(['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_nama_depan')->textInput(['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_nama_belakang')->textInput(['disabled'=>$isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($model, 'u_jkel')->dropDownList(['L'=>'Laki-laki','P'=>'Perempuan'],['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_tgl_lahir')->widget(DatePicker::className(),[
                    'dateFormat' => 'dd-MM-yyyy',
                    'options'=>['class'=>'form-control','autocomplete'=>'off','disabled'=>$isRegClose,'readonly'=>$isRegClose ? false : 'readonly'],
                    'clientOptions'=>[
                        'maxDate'=>date('d-m-Y'),
                        'changeMonth'=>true,
                        'changeYear'=>true,
                    ]
                ]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_tmpt_lahir')->textInput(['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_no_hp')->textInput(['disabled'=>$isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'u_agama')->dropDownList(ArrayHelper::map($agama,'Kode','Agama'),['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_status_nikah')->dropDownList(['K'=>'Kawin','T'=>'Belum Kawin','J'=>'Janda','D'=>'Duda'],['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_kedudukan_keluarga')->textInput(['disabled'=>$isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'u_alamat')->textInput(['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_kab')->textInput(['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_provinsi')->textInput(['disabled'=>$isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'u_pendidikan')->dropDownList($pendidikan,['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_pekerjaan')->dropDownList(ArrayHelper::map($pekerjaan,'Nomor','PerkerjaanJabatan'),['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_jabatan_pekerjaan')->textInput(['disabled'=>$isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'u_nama_ayah')->textInput(['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_nama_ibu')->textInput(['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_nama_pasangan')->textInput(['disabled'=>$isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'u_anggota_darurat')->dropDownList(['1'=>'Iya','0'=>'Tidak'],['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'u_anggota_darurat_ket')->textInput(['disabled'=>$isRegClose ? $isRegClose : ( $model->u_anggota_darurat!=1 ? true : false ) ]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'u_tgl_terakhir_mcu')->widget(DatePicker::className(),[
                        'dateFormat' => 'dd-MM-yyyy',
                        'options'=>['class'=>'form-control','autocomplete'=>'off','disabled'=>$isRegClose,'readonly'=>$isRegClose ? false : 'readonly'],
                        'clientOptions'=>[
                            'maxDate'=>date('d-m-Y'),
                            'changeMonth'=>true,
                            'changeYear'=>true,
                        ]
                    ]) ?>
                </div>
                <div class="col-md-4"><?= $form->field($model, 'u_dokter')->textInput(['disabled'=>$isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_alamat_dokter')->textInput(['disabled'=>$isRegClose]) ?></div>
            </div>
            <?php
            if(!$isRegClose){
                ?>
                <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-save"></i> Simpan</button>
                <span class="btn-next" style="<?php echo $model->u_biodata_finish_at==NULL ? "display:none;" : "" ?>"><a href="<?php echo Url::to(['berkas']); ?>" class="btn btn-default"><i class="fa fa-arrow-circle-o-right"></i> Selanjutnya (Upload Berkas)</a></span>
                <?php
            }
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
<?php
if($model->u_finish_at!=NULL){
    ?>
    <a href="<?php echo Url::to(['download-bukti']); ?>" target="_blank" class="btn btn-block btn-success" title="download bukti pendaftaran"><i class="fa fa-download"></i> Bukti Pendaftaran</a>
    <?php
}
?>