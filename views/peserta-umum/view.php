<?php
use yii\helpers\Url;
use yii\widgets\DetailView;
$this->title="Detail Peserta";
if($model['ud_rm']==NULL){
    $this->registerJs("
        $('.btn-status').click(function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            setBtnLoading(btn,'Loading...');
            $.post('".Url::to(['status-form'])."',{id:".$model['ud_id']."},function(result){
                $('#mymodal').html(result).modal('show');
                resetBtnLoading(btn,htm);
            });
        });
    ");
}
?>
<h4><?php echo $this->title; ?></h4>
<p>
    <a href="<?php echo Url::to(['index']) ?>" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> kembali</a>
    <?php
    if($model['ud_rm']==NULL){
        ?><a href="#" class="btn btn-sm btn-primary btn-status" title="klik untuk ubah status peserta baru"><i class="fa fa-recycle"></i> Ubah Status</a><?php
    }
    ?>
</p>
<div class="row">
    <div class="col-md-3">
        <a href="<?php echo Url::to(['get-ktp','id'=>$model['ud_id']]); ?>" target="_blank"><img src="<?php echo Url::to(['get-ktp','id'=>$model['ud_id']]); ?>" width="100%"></a>
        <a class="btn btn-success btn-sm btn-block" style="margin-top:10px;">
            <b>Status Pendaftaran :</b> 
            <?php
            if($model['ud_approve_status']=='0'){
                echo "Tidak Disetujui";
            }elseif($model['ud_approve_status']=='1'){
                echo "Revisi";
            }elseif($model['ud_approve_status']=='2'){
                echo "Disetujui";
            }else{
                echo "Baru";
            }
            ?>
        </a>
        <?php
        if($model['ud_approve_status']=='0' || $model['ud_approve_status']=='1'){
            if($model['ud_approve_ket']!=NULL){
                echo '<div class="well well-sm">" '.$model['ud_approve_ket'].' "</div>';
            }
        }
        ?>
    </div>
    <div class="col-md-9">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label'=>'No. Rekam Medis',
                    'visible'=>$model['ud_rm']!=NULL ? true : false,
                    'value'=>$model['ud_rm']
                ],
                [
                    'label'=>'NIK',
                    'value'=>$model['ud_nik']
                ],
                [
                    'label'=>'Email',
                    'value'=>$model['ud_email']
                ],
                [
                    'label'=>'Nama Lengkap',
                    'value'=>$model['ud_nama']
                ],
                [
                    'label'=>'Jenis Kelamin',
                    'value'=>$model['ud_jkel']=='L' ? 'Laki-laki' : 'Perempuan'
                ],
                [
                    'label'=>'Tempat/Tgl Lahir',
                    'value'=>$model['ud_tmpt_lahir'].' / '.date('d-m-Y',strtotime($model['ud_tgl_lahir']))
                ],
                [
                    'label'=>'Alamat',
                    'value'=>$model['ud_alamat'].', RT '.$model['ud_rt'].' RW '.$model['ud_rw'].', KECAMATAN '.$model['ud_kecamatan'].', KABUPATEN '.$model['ud_kabupaten'].', PROVINSI '.$model['ud_provinsi']
                ],
                [
                    'label'=>'No. HP',
                    'value'=>$model['ud_telp']
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
                    'value'=>$status_marital[$model['ud_status_nikah']]
                ],
                [
                    'label'=>'Kedudukan Keluarga',
                    'value'=>$model['ud_kedudukan_keluarga']
                ],
                [
                    'label'=>'Istri Ke',
                    'visible'=>$model['ud_kedudukan_keluarga']=='istri' ? true : false,
                    'value'=>$model['ud_istri_ke']
                ],
                [
                    'label'=>'Pendidikan',
                    'value'=>$pendidikan[$model['ud_pendidikan']]
                ],
                [
                    'label'=>'Pekerjaan',
                    'value'=>$model['pekerjaan']!=NULL ? $model['pekerjaan']['PerkerjaanJabatan'] : ''
                ],
                [
                    'label'=>'Jabatan Pekerjaan',
                    'value'=>$model['ud_jabatan_pekerjaan']
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
                    'value'=>$model['ud_nama_ayah']
                ],
                [
                    'label'=>'Nama Ibu',
                    'value'=>$model['ud_nama_ibu']
                ],
                [
                    'label'=>'Nama Pasangan',
                    'visible'=>$model['ud_status_nikah']=='K' ? true : false,
                    'value'=>$model['ud_nama_pasangan']
                ],
                [
                    'label'=>'Peserta Termasuk Anggota Tim Penangangan Keadaan Darurat',
                    'visible'=>$model['ud_anggota_darurat']==1 ? true : false,
                    'value'=>$model['ud_anggota_darurat']=='1' ? "Iya" : "Tidak"
                ],
                [
                    'label'=>'Nama Tim Penangangan Keadaan Darurat',
                    'visible'=>$model['ud_anggota_darurat_ket']!=NULL ? true : false,
                    'value'=>$model['ud_anggota_darurat_ket']
                ],
            ],
        ]) ?>
    </div>
</div>
<h4>Riwayat Pemeriksaan</h4>