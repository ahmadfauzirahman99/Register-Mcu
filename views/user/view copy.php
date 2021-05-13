<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
$this->title = $model->u_nik;
\yii\web\YiiAsset::register($this);
$this->registerjs("
$('.btn-status').click(function(e){
    e.preventDefault();
    $.ajax({
        url:'".Url::to(['status-form'])."',
        type:'post',
        data:{id:".$model->u_id."},
        success:function(result){
            $('#mymodal').html(result).modal({show:true});
        },
        error:function(xhr,status,error){
            console.log(error);
        }
    }); Rsud44_Rec
});
");
?>
<div class="user-view">
    <h3><strong>Detail Peserta : <?= Html::encode($this->title) ?></strong></h3>
    <p>
        <a href="<?php echo Url::to(['index']); ?>" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Kembali</a>
        <a href="#" class="btn btn-primary btn-status"><i class="glyphicon glyphicon-random"></i> Update Status</a>
    </p>
    <?php
    if($model->u_lulus_reg!=NULL){
        ?><div class="alert <?php echo $model->u_lulus_reg=='0' ? 'alert-danger' : 'alert-success' ?>">
            <h4><?php echo $model->u_lulus_reg=='0' ? '<i class="glyphicon glyphicon-remove"></i> Peserta Tidak Lulus' : '<i class="glyphicon glyphicon-ok"></i> Peserta Lulus' ?></h4>
            <?php
            if($model->u_ket!=NULL){
                echo "<p>Alasan : ".$model->u_ket."</p>";
            }
            ?>
        </div><?php
    }
    ?>
    <h4><strong>Biodata</strong></h4>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'u_nik',
            'u_nama',
            'u_email:email',
            'u_alamat',
            [
                'label'=>'Jenis Kelamin',
                'value'=>$model->u_jkel=='l' ? 'Laki-laki' : 'Perempuan'
            ],
            [
                'label'=>'Tanggal Lahir',
                'value'=>date('d-m-Y',strtotime($model->u_tgl_lahir)),
            ],
            'u_tmpt_lahir',
            'u_no_hp',
            'u_social_media',
            [
                'label'=>'Pendidikan Terakhir',
                'value'=>$model->pendidikan->jp_nama,
            ],
            'u_jurusan',
            'u_instansi',
            'u_ipk',
            [
                'label'=>'Selesai Pendaftaran',
                'format'=>'raw',
                'value'=>$model->u_finish_reg=='0' ? '<div class="btn btn-sm btn-warning">Pendaftaran Belum Selesai</div>' : '<div class="btn btn-sm btn-success">Pendaftaran Selesai</div>'
            ],
            [
                'label'=>'Tanggal Selesai Pendaftaran',
                'value'=>date('d-m-Y H:i:s',strtotime($model->u_finish_reg_at))
            ]
        ],
    ]) ?>
    <h4><strong>Formasi Lamaran</strong></h4>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label'=>'Formasi Lamaran',
                'value'=>$model->formasi!=NULL ? $model->formasi->f_nama_formasi.' ('.$model->formasi->f_pendidikan.')' : NULL,
            ],
            [
                'label'=>'Jalur Perawat',
                'visible'=>$model->u_jalur_perawat!=NULL ? true : false,
                'value'=>$model->u_jalur_perawat=='p' ? 'Prestasi' : 'Seleksi',
            ]
        ],
    ]) ?>
    <h4><strong>Berkas</strong></h4>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>Nama Berkas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if($model->berkas!=NULL){
                ?>
                <tr>
                    <th>KTP</th>
                    <td><?php echo $model->berkas->ub_ktp!=NULL ? '<a href="'.Url::to(['site/file','id'=>$model->u_id,'data'=>'ktp']).'" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-file"></i> Lihat Berkas</a>' : '<button type="button" class="btn btn-warning">Belum Upload</button>' ?></td>
                </tr>
                <tr>
                    <th>Photo</th>
                    <td><?php echo $model->berkas->ub_photo!=NULL ? '<a href="'.Url::to(['site/file','id'=>$model->u_id,'data'=>'photo']).'" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-file"></i> Lihat Berkas</a>' : '<button type="button" class="btn btn-warning">Belum Upload</button>' ?></td>
                </tr>
                <tr>
                    <th>Ijazah</th>
                    <td><?php echo $model->berkas->ub_ijazah!=NULL ? '<a href="'.Url::to(['site/file','id'=>$model->u_id,'data'=>'ijazah']).'" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-file"></i> Lihat Berkas</a>' : '<button type="button" class="btn btn-warning">Belum Upload</button>' ?></td>
                </tr>
                <tr>
                    <th>Akta Kelahiran</th>
                    <td><?php echo $model->berkas->ub_akta!=NULL ? '<a href="'.Url::to(['site/file','id'=>$model->u_id,'data'=>'akta']).'" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-file"></i> Lihat Berkas</a>' : '<button type="button" class="btn btn-warning">Belum Upload</button>' ?></td>
                </tr>
                <tr>
                    <th>Sertifikat PPGD / BTCLS / Wound Care / Bedah Dasar / ICU / PICU</th>
                    <td><?php echo $model->berkas->ub_sertifikat!=NULL ? '<a href="'.Url::to(['site/file','id'=>$model->u_id,'data'=>'sertifikat']).'" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-file"></i> Lihat Berkas</a>' : '<button type="button" class="btn btn-warning">Belum Upload</button>' ?></td>
                </tr>
                <tr>
                    <th>Surat Keterangan Sehat dari Dokter</th>
                    <td><?php echo $model->berkas->ub_surat_sehat!=NULL ? '<a href="'.Url::to(['site/file','id'=>$model->u_id,'data'=>'sehat']).'" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-file"></i> Lihat Berkas</a>' : '<button type="button" class="btn btn-warning">Belum Upload</button>' ?></td>
                </tr>
                <tr>
                    <th>STR</th>
                    <td><?php echo $model->berkas->ub_str!=NULL ? '<a href="'.Url::to(['site/file','id'=>$model->u_id,'data'=>'str']).'" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-file"></i> Lihat Berkas</a>' : '<button type="button" class="btn btn-warning">Belum Upload</button>' ?></td>
                </tr>
                <tr>
                    <th>Surat Pernyataan</th>
                    <td><?php echo $model->berkas->ub_pernyataan!=NULL ? '<a href="'.Url::to(['site/file','id'=>$model->u_id,'data'=>'pernyataan']).'" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-file"></i> Lihat Berkas</a>' : '<button type="button" class="btn btn-warning">Belum Upload</button>' ?></td>
                </tr>
                <?php
            }else{
                ?>
                <tr>
                    <td colspan="2">Data belum diupload</td>
                </tr>
                <?php
            }
        ?>
        </tbody>
    </table>
    
</div>
