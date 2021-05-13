<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\jui\DatePicker;
$this->title="Detail Peserta";
if($model['ud_approve_status']!='2'){
    $this->registerJs("
        $('.btn-status').click(function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            setBtnLoading(btn,'Loading...');
            $.post('".Url::to(['peserta-status-form'])."',{id:".$model['ud_id']."},function(result){
                $('#mymodal').html(result).modal('show');
                resetBtnLoading(btn,htm);
            });
        });
    ");
}
?>
<h4><?php echo $this->title; ?></h4>
<p>
    <a href="<?php echo Url::to(['peserta-list']) ?>" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> kembali</a>
    <?php
    if($model['ud_approve_status']!='2' && $model['ud_update_biodata_at']!=NULL && $model['ud_ktp']!=NULL){
        ?><a href="#" class="btn btn-sm btn-primary btn-status" title="klik untuk ubah status peserta baru"><i class="fa fa-recycle"></i> Ubah Status</a><?php
    }
    ?>
</p>
<div class="row">
    <div class="col-md-4">
        <?php
        if($model['ud_ktp']!=NULL){
            ?>
            <a href="<?php echo Url::to(['get-ktp','id'=>$model['ud_id']]); ?>" target="_blank">
                <img src="<?php echo Url::to(['get-ktp','id'=>$model['ud_id']]); ?>" width="100%">
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
            </div>
            <div class="col-md-6">
                <a href="#" class="btn btn-info btn-sm btn-block" data-toggle="tooltip" title="jenis pasien" data-placement="bottom" style="margin-top:10px;">
                    <b>Jenis : </b><?php echo $model['ud_is_pasien_baru']=='y' ? 'Pasien Baru' : 'Pasien Lama' ?>
                </a>
            </div>
        </div>
        <?php
        if($model['ud_approve_status']=='0' || $model['ud_approve_status']=='1'){
            if($model['ud_approve_ket']!=NULL){
                echo '<div class="well well-sm">" '.$model['ud_approve_ket'].' "</div>';
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
                    'value'=>$model['ud_jkel']!=NULL ? $model['ud_jkel']=='L' ? 'Laki-laki' : 'Perempuan' : '',
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
                    'value'=>$model['ud_status_nikah']!=NULL ? $status_marital[$model['ud_status_nikah']] : ''
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
                    'value'=>$model['ud_pendidikan']!=NULL ? $pendidikan[$model['ud_pendidikan']] : ''
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
<h4>Riwayat Pemeriksaan <b><?php echo $model['ud_nama']; ?></b></h4>
<?php Pjax::begin(['id'=>'pjax-riwayat']); ?>
<?= GridView::widget([
	'id'=>'grid-riwayat',
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
        [
            'label'=>'Tanggal Pemeriksaan',
            'attribute'=>'u_tgl_periksa',
            'value'=>function($d){
                return $d->u_tgl_periksa!=NULL ? date('d-m-Y',strtotime($d->u_tgl_periksa)) : NULL;
            },
            'filter'=>DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'u_tgl_periksa',
                'options'=>['class'=>'form-control','id'=>'u_tgl_periksa'],
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'autoclose'=>true,
                    'changeYear'=>true,
                    'changeMonth'=>true,
                ]
            ])
        ],
        [
            'label'=>'Jenis Pemeriksaan',
            'attribute'=>'u_jenis_mcu_id',
            'value'=>function($d){
                return $d->jenismcu!=NULL ? $d->jenismcu->jm_nama : NULL;
            },
            'filter'=>ArrayHelper::map($jenis_mcu,'jm_id','jm_nama')
        ],
		[
			'class' => 'yii\grid\ActionColumn',
			'header'=>'-',
			'template' => '{view}',
			'buttons'=>[
				'view'=>function($url,$m) use($model){
					return "<a href='".Url::to(['peserta-detail-riwayat','id'=>$m->u_id,'user'=>$model['ud_id']])."' class='btn btn-info btn-sm' title='lihat data isian' data-pjax='0'  data-toggle='tooltip'><i class='fa fa-search'></i></a>";
				},
			]
		],
	],
]); ?>
<?php Pjax::end(); ?>