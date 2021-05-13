<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title="Setting Aplikasi";
$this->registerJs("
    $('input[name=\'jam\']').inputmask({
        mask:'99:99'
    });
    function uploadPedoman(form,btn){
        var htm=btn.html();
        var el=form.find('input[name=\'berkas\']');
        var file = el[0].files[0];
        var type=el.attr('data-type');
        if(file){
            setBtnLoading(btn);
            var formData = new FormData();
            formData.append('berkas', file);
            formData.append('type', type);
            $.ajax({
                url:'".Url::to(['pedoman-pemeriksaan-upload'])."',
                type:'post',
                dataType:'json',
                data:formData,
                contentType: false,
                cache: false,
                processData:false,
                success:function(result){
                    if(result.status){
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
        }else{
            errorMsg('Silahkan pilih file');
        }
    }
    function updateInformasi(form,btn){
        var htm=btn.html();
        setBtnLoading(btn);
        $.ajax({
            url:'".url::to(['informasi-save'])."',
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
                errorMsg(error);
                resetBtnLoading(btn,htm);
            }
        });
    }
    $('.form-upload-pedoman-pemeriksaan-umum').find('.btn-submit').click(function(e){
        var form=$('.form-upload-pedoman-pemeriksaan-umum');
        var btn=$(this);
        uploadPedoman(form,btn);
    });
    $('.form-upload-pedoman-pemeriksaan-instansi').find('.btn-submit').click(function(e){
        var form=$('.form-upload-pedoman-pemeriksaan-instansi');
        var btn=$(this);
        uploadPedoman(form,btn);
    });
    $('#form-info-peserta').on('beforeSubmit',function(e){
        e.preventDefault();
        var form=$(this);
        var btn=form.find('.btn-submit');
        updateInformasi(form,btn);
    }).submit(function(e){ e.preventDefault(); });
    $('#form-info-instansi').on('beforeSubmit',function(e){
        e.preventDefault();
        var form=$(this);
        var btn=form.find('.btn-submit');
        updateInformasi(form,btn);
    }).submit(function(e){ e.preventDefault(); });
    $('#form-batas-jam-daftar').on('beforeSubmit',function(e){
        e.preventDefault();
        var form=$(this);
        var btn=form.find('.btn-submit');
        var htm=btn.html();
        setBtnLoading(btn);
        $.ajax({
            url:'".Url::to(['batas-daftar-save'])."',
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
                errorMsg(error);
                resetBtnLoading(btn,htm);
            }
        });
    }).submit(function(e){ e.preventDefault(); });
");
?>
<h3><?= $this->title ?></h3>
<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin(['id'=>'form-info-peserta','options'=>['class'=>'well'] ]); ?>
            <label>Informasi Singkat Tentang Pendaftaran Peserta Umum</label>
            <input type="hidden" name="type" value="u">
            <textarea name="deskripsi" class="form-control" rows='5'><?php echo $setting['info_umum']; ?></textarea>
            <button type="submit" class="btn btn-success btn-sm btn-submit" style="margin-top:10px;"><i class="fa fa-save"></i> Simpan</button>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-6">
        <?php $form = ActiveForm::begin(['id'=>'form-info-instansi','options'=>['class'=>'well'] ]); ?>
            <label>Informasi Singkat Tentang Pendaftaran Instansi/Perusahaan</label>
            <input type="hidden" name="type" value="i">
            <textarea name="deskripsi" class="form-control" data-type="instansi" rows='5'><?php echo $setting['info_instansi']; ?></textarea>
            <button type="submit" class="btn btn-success btn-sm btn-submit" style="margin-top:10px;"><i class="fa fa-save"></i> Simpan</button>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="well form-upload-pedoman-pemeriksaan-umum">
            <label>Upload Berkas Pedoman Pemeriksaan Peserta Umum (PDF)</label>
            <input type="file" name="berkas" class="form-control" accept="application/pdf" data-type="umum">
            <button type="submit" class="btn btn-success btn-sm btn-submit" style="margin-top:10px;"><i class="fa fa-upload"></i> Upload</button>
            <a href="<?php echo Url::to(['pedoman-pemeriksaan-download','q'=>'u']); ?>" class="btn btn-info btn-sm btn-display" style="margin-top:10px;" target="_blank"><i class="fa fa-download"></i> Lihat Berkas</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="well form-upload-pedoman-pemeriksaan-instansi">
            <label>Upload Berkas Pedoman Pemeriksaan Instansi (PDF)</label>
            <input type="file" name="berkas" class="form-control" accept="application/pdf" data-type="instansi">
            <button type="submit" class="btn btn-success btn-sm btn-submit" style="margin-top:10px;"><i class="fa fa-upload"></i> Upload</button>
            <a href="<?php echo Url::to(['pedoman-pemeriksaan-download','q'=>'i']); ?>" class="btn btn-info btn-sm btn-display" style="margin-top:10px;" target="_blank"><i class="fa fa-download"></i> Lihat Berkas</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin(['id'=>'form-batas-jam-daftar','options'=>['class'=>'well'] ]); ?>
            <label>Batas Jam Pendaftaran</label>
            <input type="text" name="jam" class="form-control" value="<?php echo $setting['batas_jam_daftar']; ?>">
            <button type="submit" class="btn btn-success btn-sm btn-submit" style="margin-top:10px;"><i class="fa fa-save"></i> Simpan</button>
        <?php ActiveForm::end(); ?>
    </div>
</div>