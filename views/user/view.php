<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
$this->title = $model['u_nik'];
\yii\web\YiiAsset::register($this);
$this->registerjs("
$('.btn-status').click(function(e){
    e.preventDefault();
    var btn=$(this);
    var htm=btn.html();
    setBtnLoading(btn,'Loading...');
    $.ajax({
        url:'".Url::to(['status-form'])."',
        type:'post',
        data:{id:".$model['u_id']."},
        success:function(result){
            $('#mymodal').html(result).modal({show:true});
            resetBtnLoading(btn,htm);
        },
        error:function(xhr,status,error){
            console.log(error);
            resetBtnLoading(btn,htm);
        }
    });
});
$('#checkbox').change(function(){
	var el = $(this);
	var vl=0;
	if(el.prop('checked')){
		vl=1;
	}
	$.ajax({
		url:'".Url::to(['status-verifikasi'])."',
		type:'post',
		dataType:'json',
		data:{status:vl},
		success:function(result){
			
		},
		error:function(xhr,status,error){
			errorMsg(error);
		}
	})
});
");
$this->registerCss("
#checkbox{
	transform: scale(2);
}
");
?>
<div class="user-view">
    <h3><strong>Detail Peserta : <?= Html::encode($this->title) ?></strong></h3>
    <p>
        <a href="<?php echo Url::to(['index']); ?>" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Kembali</a>
        <a href="#" class="btn btn-primary btn-status"><i class="fa fa-superpowers" aria-hidden="true"></i> Status Verifikasi Adm</a>
		<?php
		if($verifikator!=NULL){
			?><button type="button" class="btn btn-info"><i class="fa fa-user"></i> <?php echo "Verifikator : ".$verifikator." ( ".date('d-m-Y H:i',strtotime($model['u_verify_at']))." )"; ?></button><?php
		}
		if(Yii::$app->user->identity->u_level==3){
			?>
			<!--<label style="font-size:17px; padding-top:5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkbox" checked>&nbsp;&nbsp;Sedang Diverifikasi ?</label>-->
			<?php
		}
		?>
	</p>
    <?php
    if($model['u_lulus_reg']!=NULL){
        ?><div class="alert <?php echo $model['u_lulus_reg']=='0' ? 'alert-danger' : ( $model['u_lulus_reg']=='1' ? 'alert-success' : 'alert-warning') ?>">
            <h4><?php echo $model['u_lulus_reg']=='0' ? '<i class="glyphicon glyphicon-remove"></i> Peserta Tidak Memenuhi Syarat' : ($model['u_lulus_reg']=='1' ? '<i class="glyphicon glyphicon-ok"></i> Peserta Memenuhi Syarat' : '<i class="fa fa-warning"></i> Dipertimbangkan') ?></h4>
            <?php
            if($model['u_lulus_reg']=='0' || $model['u_lulus_reg']=='2'){
                if($model['u_ket']!=NULL){
                    echo "<p>Alasan : ".$model['u_ket']."</p>";
                }
            }
            ?>
        </div><?php
    }
    ?>
    <h4><strong>Biodata</strong></h4>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label'=>'NIK',
                'value'=>$model['u_nik'],
            ],
            [
                'label'=>'Nama',
                'value'=>$model['u_nama'],
            ],
            [
                'label'=>'Email',
                'value'=>$model['u_email'],
            ],
            [
                'label'=>'Alamat',
                'value'=>$model['u_alamat'],
            ],
            [
                'label'=>'Jenis Kelamin',
                'value'=>$model['u_jkel']=='l' ? 'Laki-laki' : 'Perempuan'
            ],
            [
                'label'=>'Tanggal Lahir',
                'value'=>date('d-m-Y',strtotime($model['u_tgl_lahir'])),
            ],
            [
                'label'=>'Tempat Lahir',
                'value'=>$model['u_tmpt_lahir'],
            ],
            [
                'label'=>'No. Hp',
                'value'=>$model['u_no_hp'],
            ],
            [
                'label'=>'Link Sosial Media',
                'value'=>$model['u_social_media'],
            ],
            [
                'label'=>'Pendidikan Terakhir',
                'value'=>$model['jenis_pendidikan'],
            ],
            [
                'label'=>'Jurusan',
                'value'=>$model['u_jurusan']
            ],
            [
                'label'=>'Universitas',
                'value'=>$model['u_instansi']
            ],
            [
                'label'=>'IPK',
                'value'=>$model['u_ipk']
            ],
            [
                'label'=>'Selesai Pendaftaran',
                'format'=>'raw',
                'value'=>$model['u_finish_reg']=='0' ? '<div class="btn btn-sm btn-warning"><i class="fa fa-close"></i> Pendaftaran Belum Selesai</div>' : '<div class="btn btn-sm btn-success"><i class="fa fa-check"></i> Pendaftaran Selesai</div>'
            ],
            [
                'label'=>'Tanggal Selesai Pendaftaran',
                'format'=>'raw',
                'value'=>function($data){
                    if($data['u_finish_reg_at'] !=NULL){
                        $str=date('d-m-Y H:i:s',strtotime($data['u_finish_reg_at']));
                        if(strtotime(date('d-m-Y H:i:s',strtotime($data['u_finish_reg_at'])))>strtotime(Yii::$app->params['reg_close_date'])){
                            $cls="btn-danger";
                            $icon="close";
                        }else{
                            $cls="btn-success";
                            $icon="check-circle";
                        }
                        $str.=" <button type='button' class='btn btn-sm ".$cls."'><i class='fa fa-".$icon."'></i> ".Yii::$app->params['reg_close_date']."</button>";
                        return $str;
                    }
                    return '';
                }
            ]
        ],
    ]) ?>
    <h4><strong>Formasi Lamaran</strong></h4>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label'=>'Formasi Lamaran',
                'value'=>$model['f_nama_formasi'].' ('.$model['f_pendidikan'].')',
            ],
            [
                'label'=>'Jalur Perawat',
                'visible'=>$model['u_formasi_id']==9 && $model['u_jalur_perawat']!=NULL ? true : false,
                'value'=>$model['u_jalur_perawat']=='p' ? 'Prestasi' : 'Seleksi',
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
        if(count($berkas_syarat)>0){
            foreach($berkas_syarat as $bs){
                if($bs['fs_formasi_id']==9 && $bs['fs_jenis_berkas_id']==9){
                    if($model['u_formasi_id']==9 && $model['u_jalur_perawat']=='s'){
                        continue;
                    }
                }
                ?>
                <tr>
                    <td>
					<?php echo $bs['jenisBerkas']!=NULL ? $bs['jenisBerkas']['jb_nama'] : NULL; ?></td>
                    <td><?php echo $bs['userBerkas']!=NULL ? '<a href="'.Url::to(['file','id'=>$bs['userBerkas']['ub_id']]).'" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-file"></i> Lihat Berkas</a>' : '<a href="#" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Tidak Ada Berkas</a>'; ?></td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
</div>