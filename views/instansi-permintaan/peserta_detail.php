<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\jui\DatePicker;
$this->title="Detail Peserta Instansi";
if($model['u_approve_status']!='2'){
    $this->registerJs("
        $('.btn-status').click(function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            setBtnLoading(btn,'Loading...');
            $.post('".Url::to(['peserta-verify-form'])."',{id:".$model['u_id']."},function(result){
                $('#mymodal').html(result).modal('show');
                resetBtnLoading(btn,htm);
            });
        });
    ");
}
?>
<h4><?php echo $this->title; ?></h4>
<p>
    <a href="<?php echo Url::to(['view','id'=>$up]) ?>" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> kembali</a>
    <?php
    // if($model['u_approve_status']!='2' && $model['u_finish_at']==NULL && !empty($model['u_ktp'])){
    if($model['u_approve_status']!='2' && $model['u_finish_at']==NULL){
        ?><a href="#" class="btn btn-sm btn-primary btn-status" title="klik untuk ubah status peserta baru"><i class="fa fa-recycle"></i> Ubah Status</a><?php
    }
    ?>
    <!-- <a href="#" class="btn btn-sm btn-success btn-ganti" title="ganti rekam medis"><i class="fa fa-edit"></i> Ganti RM</a> -->
</p>
<div class="row">
    <div class="col-md-4">
        <?php
        if($model['u_ktp']!=NULL){
            ?>
            <a href="<?php echo Url::to(['get-ktp','id'=>$model['u_id']]); ?>" target="_blank">
                <img src="<?php echo Url::to(['get-ktp','id'=>$model['u_id']]); ?>" width="100%">
            </a>
            <?php
        }else{
            echo "<h5><b><center>KTP BELUM DIUPLOAD</center></b></h5>";
        }
        ?>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-success btn-sm btn-block" data-toggle="tooltip" title="status pendaftaran" data-placement="bottom" style="margin-top:10px;">
                    <b>Status :</b> 
                    <?php
                    if($model['u_approve_status']=='0'){
                        echo "Tidak Disetujui";
                    }elseif($model['u_approve_status']=='1'){
                        echo "Revisi";
                    }elseif($model['u_approve_status']=='2'){
                        echo "Disetujui";
                    }else{
                        echo "Baru";
                    }
                    ?>
                </a>
            </div>
            <div class="col-md-6">
                <a href="#" class="btn btn-info btn-sm btn-block" data-toggle="tooltip" title="jenis pasien" data-placement="bottom" style="margin-top:10px;">
                    <b>Jenis : </b><?php echo $model['u_is_pasien_baru']!=NULL ? ($model['u_is_pasien_baru']=='y' ? 'Pasien Baru' : 'Pasien Lama') : '' ?>
                </a>
            </div>
        </div>
        <?php
        if($model['u_approve_status']=='0' || $model['u_approve_status']=='1'){
            if($model['u_approve_ket']!=NULL){
                echo '<div class="well well-sm">" '.$model['u_approve_ket'].' "</div>';
            }
        }
        ?>
    </div>
    <div class="col-md-8">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label'=>'No. Rekam Medis',
                    'visible'=>$model['u_rm']!=NULL ? true : false,
                    'value'=>$model['u_rm']
                ],
                [
                    'label'=>'NIK',
                    'value'=>$model['u_nik']
                ],
                [
                    'label'=>'Email',
                    'value'=>$model['u_email']
                ],
                [
                    'label'=>'Nama Lengkap',
                    'value'=>$model['u_nama_depan']
                ],
                [
                    'label'=>'Jenis Kelamin',
                    'value'=>$model['u_jkel']!=NULL ? $model['u_jkel']=='L' ? 'Laki-laki' : 'Perempuan' : '',
                ],
                [
                    'label'=>'Tempat/Tgl Lahir',
                    'value'=>$model['u_tmpt_lahir'].' / '.date('d-m-Y',strtotime($model['u_tgl_lahir']))
                ],
                [
                    'label'=>'Alamat',
                    'value'=>$model['u_alamat']
                ],
                [
                    'label'=>'No. HP',
                    'value'=>$model['u_no_hp']
                ],
                [
                    'label'=>'Agama',
                    'value'=>$model['agama']!=NULL ? $model['agama']['Agama'] : ''
                ],
            ],
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label'=>'Status Pernikahan',
                    'value'=>$model['u_status_nikah']!=NULL ? $status_marital[$model['u_status_nikah']] : ''
                ],
                [
                    'label'=>'Kedudukan Keluarga',
                    'value'=>$model['u_kedudukan_keluarga']
                ],
                [
                    'label'=>'Istri Ke',
                    'visible'=>$model['u_kedudukan_keluarga']=='istri' ? true : false,
                    'value'=>$model['u_istri_ke']
                ],
                [
                    'label'=>'Pendidikan',
                    'value'=>$model['u_pendidikan']!=NULL ? $pendidikan[$model['u_pendidikan']] : ''
                ],
                [
                    'label'=>'Pekerjaan',
                    'value'=>$model['pekerjaan']!=NULL ? $model['pekerjaan']['PerkerjaanJabatan'] : ''
                ],
                [
                    'label'=>'Jabatan Pekerjaan',
                    'value'=>$model['u_jabatan_pekerjaan']
                ],
            ],
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label'=>'Nama Ayah',
                    'value'=>$model['u_nama_ayah']
                ],
                [
                    'label'=>'Nama Ibu',
                    'value'=>$model['u_nama_ibu']
                ],
                [
                    'label'=>'Nama Pasangan',
                    'visible'=>$model['u_status_nikah']=='K' ? true : false,
                    'value'=>$model['u_nama_pasangan']
                ],
                [
                    'label'=>'Peserta Termasuk Anggota Tim Penangangan Keadaan Darurat',
                    'visible'=>$model['u_anggota_darurat']==1 ? true : false,
                    'value'=>$model['u_anggota_darurat']=='1' ? "Iya" : "Tidak"
                ],
                [
                    'label'=>'Nama Tim Penangangan Keadaan Darurat',
                    'visible'=>$model['u_anggota_darurat_ket']!=NULL ? true : false,
                    'value'=>$model['u_anggota_darurat_ket']
                ],
            ],
        ]) ?>
    </div>
</div>