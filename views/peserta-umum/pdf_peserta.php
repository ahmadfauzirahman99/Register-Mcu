<?php
use app\models\PelayananMcu;
use yii\helpers\Url;
use app\models\Antrian;
?>
<style>
.header{
	text-align:center;
}
.subheader{
	text-align: center; 
    font-size:18px;
	border-top: 1px solid #000;
}
.table thead tr th{
	font-size:14px;
}
.table tbody tr td,
.table tbody tr th{
	font-size:14px;
	padding:3px;
    text-align:left;
} 
.info{
	background-color:#D9EDF7;
}
.warning{
	background-color:#FCF8E3;
}
.danger{
	background-color:#F2DEDE;
}
</style>
<div class="header">
	<img src="<?php echo Url::base() ?>/img/header_rsud.jpg" width='50%' style='padding-bottom: 10px; '>
</div>
<div class="subheader">
	<h4 style=" margin-top: 5px; margin-bottom: 0px;">DAFTAR KEHADIRAN PESERTA PEMERIKSAAN KESEHATAN<br>CPNS KEJAKSAAAN RI FORMASI TAHUN 2019</h4>
	<small>Tanggal Pemeriksaan <?php echo date('d-m-Y',strtotime($tgl)); ?> </small>
</div>
<br>
<table class="table" border="1" width="100%" style="border-collapse:collapse;">
	<thead>
		<tr>
			<th width="5%">No</th>
			<th>No. Peserta</th>
			<th>No. RM</th>
			<th>Nama Peserta</th>
			<th>Status Daftar Online</th>
			<th>Status Kehadiran</th>
			<th>Keterangan</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if(count($user)>0){
			$no=1;
			foreach($user as $u){
				$h=Yii::$app->dbpost->createCommand("select * from ".PelayananMcu::tableName()." where no_rekam_medik = :mr and kode_debitur is not null")->bindValues([':mr'=>$u['u_rm']])->queryOne();
				$antrian=NULL;
				if($h!=NULL){
					$antrian=Yii::$app->dbsimrs->createCommand("select NO_URUT from ".Antrian::tableName()." where NO_PASIEN = :rm and NO_DAFTAR = :noreg and KD_INST = 3902")->bindValues([':rm'=>$h['no_rekam_medik'],':noreg'=>$h['no_registrasi']])->queryOne();
				}
				?>
				<tr>
					<td><?php echo $no; ?></td>
					<td><?php echo $u['u_nik']; ?></td>
					<td><?php echo $u['u_rm']; ?></td>
					<td><?php echo $u['u_nama_depan']; ?></td>
					<td><?php echo $u['u_finish_at']!=NULL ? "Selesai" : "Tidak Selesai" ?></td>
					<td>
						<?php
						if($antrian!=NULL){
							if($antrian['NO_URUT']>0){
								echo "Hadir";
							}else{
								echo "Tidak Hadir";
							}
						}else{
							echo "Tidak Hadir";
						}
						?>
					</td>
					<td>
						<?php
						if($u['tgl_asli']!=$u['tgl_baru']){
							echo "Jadwal Ganti Ke<br>".date('d-m-Y',strtotime($u['tgl_baru']));
							if($antrian!=NULL){
								if($antrian['NO_URUT']>0){
									echo "( Hadir )";
								}else{
									echo "( Tidak Hadir )";
								}
							}else{
								echo "( Tidak Hadir )";
							}
						}
						?>
					</td>
				</tr>
				<?php
				$no++;
			}
		}else{
			?>
			<tr>
				<td colspan="4">Data belum tersedia</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>