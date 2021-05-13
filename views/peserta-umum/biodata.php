<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\widgets\App;
use yii\bootstrap\ActiveForm;
$this->title="Biodata Peserta";
$this->registerJs("
$('#userdaftar-ud_tgl_lahir').inputmask({
    mask:'99-99-9999'
});
$(document).find('input[name=\'ktp\']').on('change',function(){
    var el=$(this);
    var el_label=el.siblings('label');
    var label=el_label.html();
    el.attr('disabled',true);
    setBtnLoading(el_label,'Uploading...');
    var file = el[0].files[0];
    if(file){
        var formData = new FormData();
        formData.append('berkas', file);
        $.ajax({
            url:'".Url::to(['upload-ktp'])."',
            type:'post',
            dataType:'json',
            data:formData,
            contentType: false,
            cache: false,
            processData:false,
            success:function(result){
                if(result.status){
                    location.reload();
                }else{
                    errorMsg(result.msg);
                }
                resetBtnLoading(el_label,label);
                el.removeAttr('disabled').val('');
            },
            error:function(xhr,status,error){
                resetBtnLoading(el_label,label);
                el.removeAttr('disabled').val('');
                errorMsg(error);
            }
        });
    }else{
        errorMsg('Terjadi kesalahan dalam memilih file, silahkan coba kembali');
    }
});

$('#userdaftar-ud_status_nikah').change(function(e){
    e.preventDefault();
    var id=$(this).val();
    var ked=$('.wrap-kedudukan');
    var pas=$('#userdaftar-ud_nama_pasangan');
    if(id){
        if(id=='K'){
            ked.show('slow');
            pas.attr('required',true);
        }else{
            ked.hide('slow');
            pas.removeAttr('required');
        }
    }
});
$('#userdaftar-ud_kedudukan_keluarga').change(function(e){
    e.preventDefault();
    var id=$(this).val();
    var ked=$('.wrap-istri');
    if(id){
        if(id=='istri'){
            ked.show('slow');
        }else{
            ked.hide('slow');
        }
    }
});
$('#userdaftar-ud_anggota_darurat').change(function(e){
    e.preventDefault();
    var id=$(this).val();
    var v=$('.wrap-darurat');
    if(id==1){
        v.show('slow')
    }else{
        v.hide('slow');
    }
});
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Loading...');
    $.ajax({
        url:'".Url::to(['biodata-save'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                if(result.o){
                    alert(result.msg);
                    setTimeout(function(){
                        window.location.href='".url::to(['auth/umum'])."';
                    },900);
                }else{
                    successMsg(result.msg);
                }
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
if(App::isPeserta()){
    if($model->ud_approve_status!='2'){
        $this->registerJs("
        setInterval(function(){
            $.ajax({
                url:'".Url::to(['/peserta-umum/check-status-approve'])."',
                type:'post',
                dataType:'json',
                success:function(result){
                    if(result.status){
                        location.reload();
                    }else{
                        if(result.msg){
                            errorMsg(result.msg);
                        }
                    }
                },
                error:function(xhr,status,error){
                    errorMsg(error);
                }
            });
        },".Yii::$app->params['timeout']['check_verifikasi'].");
        ");
    }
}
?>
<h4><?php echo $this->title; ?></h4>
<div class="row">
    <div class="col-md-<?php echo $model->ud_rm!=NULL ? '8' : '12' ?>">
        <div class="alert alert-info">
            <?php
            if($model->ud_approve_status!='2'){
                echo "Lengkapi dan simpan biodata terlebih dahulu, kemudian upload KTP anda.";
            }
            if($model->ud_approve_status=='1' || $model->ud_approve_status=='0'){
                echo "<br><b><i class='fa fa-warning'></i> ";
                if(!empty($model->ud_approve_ket)){
                    echo $model->ud_approve_ket;
                }
                echo "</b>";
            }elseif($model->ud_approve_status=='2'){
                echo "<b><i class='fa fa-check-circle'></i> AKUN ANDA SUDAH DIVERIFIKASI, SILAHKAN MELAKUKAN PENDAFTARAN."; 
            }elseif($model->ud_approve_status=='3'){
                if($model->ud_ktp!=NULL){
                    echo "<br><strong>Silahkan menunggu persetujuan dari RSUD Arifin Achmad</strong>";
                }
            }
            ?>
            <br>
            <h5 class="text-warning text-center"><strong>SILAHKAN BACA & PAHAMI <a href="<?php echo Url::to(['informasi']); ?>" title="klik ini" target='_blank' class="text-warning">INFORMASI !!</strong></a></h5>
        </div>
    </div>
    <?php
    if($model->ud_rm!=NULL){
        ?>
        <div class="col-md-4 text-center">
            <div class="alert alert-warning">
                <h4>NO. REKAM MEDIS :<br> <?php echo $model->ud_rm; ?></h4>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<div class="row">
    <div class="col-md-8">
        <?php
        $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']);  ?>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'ud_nik')->textInput(['readonly'=>$is_disabled]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'ud_nama')->textInput(['readonly'=>$is_disabled]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'ud_email')->textInput(['readonly'=> isset($user->ud_id) && $is_disabled ? $is_disabled : false ]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?php
                    if($is_disabled){
                        $jkel[$model->ud_jkel]=$model->ud_jkel=='L' ? 'Laki-laki' : 'Perempuan';
                    }else{
                        $jkel=['L'=>'Laki-laki','P'=>'Perempuan'];
                    }
                    echo $form->field($model, 'ud_jkel')->dropDownList($jkel,['readonly'=>$is_disabled]);
                    ?>
                </div>
                <div class="col-md-4"><?= $form->field($model, 'ud_tmpt_lahir')->textInput(['readonly'=>$is_disabled]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'ud_tgl_lahir')->textInput(['readonly'=>$is_disabled]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($model, 'ud_telp')->textInput() ?></div>
                <div class="col-md-5"><?= $form->field($model, 'ud_alamat')->textInput() ?></div>
                <div class="col-md-2"><?= $form->field($model, 'ud_rt')->textInput() ?></div>
                <div class="col-md-2"><?= $form->field($model, 'ud_rw')->textInput() ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'ud_provinsi')->textInput() ?></div>
                <div class="col-md-4"><?= $form->field($model, 'ud_kabupaten')->textInput() ?></div>
                <div class="col-md-4"><?= $form->field($model, 'ud_kecamatan')->textInput() ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'ud_pendidikan')->dropDownList($pendidikan) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'ud_pekerjaan')->dropDownList(ArrayHelper::map($pekerjaan,'Nomor','PerkerjaanJabatan')) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'ud_jabatan_pekerjaan')->textInput() ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'ud_tempat_tugas')->textInput() ?></div>
                <div class="col-md-6"><?= $form->field($model, 'ud_alamat_pekerjaan')->textInput() ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'ud_agama')->dropDownList(ArrayHelper::map($agama,'Kode','Agama')) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'ud_status_nikah')->dropDownList(['T'=>'Belum Kawin','K'=>'Kawin','J'=>'Janda','D'=>'Duda']) ?></div>
            </div>
            <div class="row wrap-kedudukan" style="<?php echo $model->ud_status_nikah!="K" ? "display:none;" : "" ?>">
                <div class="col-md-4"><?= $form->field($model, 'ud_kedudukan_keluarga')->dropDownList(['kepala keluarga'=>'Kepala Keluarga','istri'=>'Istri']) ?></div>
                <div class="col-md-4 wrap-istri" style="<?php echo $model->ud_kedudukan_keluarga=='istri' ? '' : 'display:none;' ?>"><?= $form->field($model, 'ud_istri_ke')->dropDownList([1=>1,2,3,4]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'ud_nama_pasangan')->textInput(['required'=>$model->ud_status_nikah=='K' ? true : false]) ?></div>
            </div>
            <?php?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'ud_nama_ayah')->textInput() ?></div>
                <div class="col-md-6"><?= $form->field($model, 'ud_nama_ibu')->textInput() ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'ud_anggota_darurat')->dropDownList(['1'=>'Iya','0'=>'Tidak']) ?></div>
                <div class="col-md-6 wrap-darurat" style='<?php echo $model->ud_anggota_darurat=='0' ? 'display:none;' : '' ?>'><?= $form->field($model, 'ud_anggota_darurat_ket')->textInput() ?></div>
            </div>
            <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-save"></i> Simpan</button>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-4">
        <label>Upload KTP</label>
        <input type="file" name="ktp" accept="image/jpeg">
        <small></small>
        <div class="ktp-preview">
            <?php
            if(!empty($model->ud_ktp)){
                ?><a href="<?php echo Url::to(['get-ktp']); ?>" target="_blank"><img src="<?php echo Url::to(['get-ktp']); ?>" width="100%"></a><?php
            }
            ?>
        </div>
    </div>
</div>