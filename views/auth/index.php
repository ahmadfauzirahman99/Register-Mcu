<?php

use yii\helpers\Url;

$this->title = "Index";
$this->registerJs("
$('.btn-info-umum').click(function(e){
    e.preventDefault();
    var btn=$(this);
    var htm=btn.html();
    setBtnLoading(btn);
    $.post('" . Url::to(['umum-info']) . "',function(result){
        resetBtnLoading(btn,htm);
        $('.mymodal').html(result).modal({show:true});
    });
});
$('.btn-info-instansi').click(function(e){
    e.preventDefault();
    var btn=$(this);
    var htm=btn.html();
    setBtnLoading(btn);
    $.post('" . Url::to(['instansi-info']) . "',function(result){
        resetBtnLoading(btn,htm);
        $('.mymodal').html(result).modal({show:true});
    });
});
");
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2" style="padding-top:100px;">
            <center>
                <img src="<?php echo Url::base() ?>/img/logo_rsud.jpg" width='30%'><br>
                <h4><strong>PENDAFTARAN MCU RSUD ARIFIN ACHMAD PROVINSI RIAU</strong></h4>
            </center>
            <div class="row">
                <div class="col-md-6">
                    <div class="well text-center">
                        <strong>PESERTA UMUM</strong>
                        <p><small>" <?php echo $setting['info_umum']; ?> "</small></p>
                        <a href="#" data-toggle="tooltip" class="btn btn-default btn-sm btn-info-umum" title="informasi pemeriksaan peserta umum" data-placement="bottom"><i class="fa fa-info-circle"></i> Informasi</a>
                        <a href="<?php echo Url::to(['auth/umum']); ?>" data-toggle="tooltip" class="btn btn-default btn-sm" title="klik untuk lanjut ke halaman peserta umum" data-placement="bottom"><i class="fa fa-arrow-circle-right"></i> Login/Daftar</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="well text-center">
                        <strong>INSTANSI/PERUSAHAAN</strong>
                        <p><small>" <?php echo $setting['info_instansi']; ?> "</small></p>
                        <a href="#" data-toggle="tooltip" class="btn btn-default btn-sm btn-info-instansi" title="informasi pemeriksaan perusahaan/instansi" data-placement="bottom"><i class="fa fa-info-circle"></i> Informasi</a>
                        <a href="<?php echo Url::to(['auth/instansi']); ?>" data-toggle="tooltip" class="btn btn-default btn-sm" title="klik untuk ke halaman instansi/perusahaan" data-placement="bottom"><i class="fa fa-arrow-circle-right"></i> Login/Daftar</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="well text-center">
                        <strong>Informasi Penting</strong>
                        <p>Informasi Tentang Pendaftaran Pendaftaran Medical Check Up (MCU), Pasien Lama Yang Sudah Berobat di RSUD, Peserta Baru (Belum Pernah Berobat)</p>
                        <a href="" class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i> Informasi Pendaftaran MCU</a>

                        <a href="" class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i> Informasi Perserta Baru</a>
                        <a href="" class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i> Informasi Pasien Lama</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade mymodal" tabindex="false" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<?php
// echo Yii::$app->getSecurity()->generatePasswordHash('rsudaa2020');