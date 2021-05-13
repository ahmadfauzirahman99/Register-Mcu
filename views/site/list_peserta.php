<?php
use yii\helpers\Url;
use app\models\Antrian;
use app\models\PelayananMcu;
use yii\web\View;
$this->title="Daftar Peserta Tes Kesehatan Jadwal 01-09-2020";
$this->registerJs("
	$('.tglku').change(function(e){
		var tgl=$(this).val();
		window.location.href = '".Url::to(['list-peserta'])."?tgl='+tgl;
	});
	$('.btn-pdf').click(function(e){
		e.preventDefault();
		var tgl = $('.tglku').val();
		window.open('".Url::to(['peserta-pdf'])."?tgl='+tgl, '_blank');
	});
");
$this->registerCss("
.tbtb tr td{
	padding:3px;
}
");
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h3><strong>Daftar Peserta Tes Kesehatan Tanggal
				<select class="tglku">
				<?php  
				foreach($jadwal as $j){
					?><option value="<?php echo $j['j_tgl'] ?>" <?php echo $tgl==$j['j_tgl'] ? "selected" : "" ?>><?php echo date('d-m-Y',strtotime($j['j_tgl'])); ?></option><?php
				}
				?>
				</select>
				<a href="#" class="btn btn-primary btn-sm btn-pdf" title="download file pdf peserta tanggal <?php  ?>"><i class="fa fa-file-pdf-o"></i></a>
			</h3>
			<table border="1" class="tbtb" width="50%">
				<tr>
					<td>
						<table width="100%">
							<tr>
								<th>Peserta Daftar Online </th>
								<td class="daftar-online">: 1</td>
							</tr>
							<tr class="">
								<th>Peserta Tidak Daftar Online </th>
								<td class="daftar-tidak-online">: 2</td>
							</tr>
							<tr class="">
								<th>Peserta Ganti Jadwal </th>
								<td class="daftar-ganti-jadwal">: 2</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table width="100%">
							<tr>
								<th>Peserta Hadir </th>
								<td class="hadir">: 3</td>
							</tr>
							<tr>
								<th>Peserta Tidak Hadir </th>
								<td class="tidak-hadir">: 4</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table class="table table-bordered">
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
						$total_selesai_daftar=0;
						$total_tidak_selesai_daftar=0;
						$total_hadir=0;
						$total_tidak_hadir=0;
						$total_ganti_jadwal=0;
						foreach($user as $u){
							$h=Yii::$app->dbpost->createCommand("select * from ".PelayananMcu::tableName()." where no_rekam_medik = :mr and no_ujian = :noujian")->bindValues([':mr'=>$u['u_rm'],':noujian'=>$u['u_nik']])->queryOne();
							$antrian=NULL;
							if($h!=NULL){
								$antrian=Yii::$app->dbsimrs->createCommand("select NO_URUT from ".Antrian::tableName()." where NO_PASIEN = :rm and NO_DAFTAR = :noreg and KD_INST = 3902")->bindValues([':rm'=>$h['no_rekam_medik'],':noreg'=>$h['no_registrasi']])->queryOne();
							}
							$cls="";
							$title="";
							
							//total selesai daftar
							if($u['u_rm']!=NULL){
								$total_selesai_daftar++;
							}else{
								$total_tidak_selesai_daftar++;
							}
							
							//peserta hadir atau tidak
							if($antrian==NULL){
								$total_tidak_hadir++;
							}else{
								if($antrian['NO_URUT']>0){
									$total_hadir++;
								}else{
									$total_tidak_hadir++;
								}
							}
							
							if($u['tgl_asli']!=$u['tgl_baru']){
								$total_ganti_jadwal++;
								$cls="info";
								$title="jadwal peserta diubah";
							}
							if($cls==""){
								if($u['u_rm']!=NULL){
									if($antrian!=NULL){
										if($antrian['NO_URUT']<1){
											$cls="warning";
											$title="peserta belum konfirmasi";
										}
									}
								}else{
									$cls="danger";
									$title="peserta tidak melakukan pendaftaran online";
								}
							}
							?>
							<tr class="<?php echo $cls; ?>" title="<?php echo $title; ?>">
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
										echo "Jadwal Ganti Ke<br>".date('d-m-Y',strtotime($u['tgl_baru']))."<br>";
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
		</div>
	</div>
</div>
<?php
$this->registerJs("
console.log(".$total_selesai_daftar.");
$('.daftar-online').html(': ".$total_selesai_daftar."');
$('.daftar-tidak-online').html(': ".$total_tidak_selesai_daftar."');
$('.hadir').html(': ".$total_hadir."');
$('.tidak-hadir').html(': ".$total_tidak_hadir."');
$('.daftar-ganti-jadwal').html(': ".$total_ganti_jadwal."');
",View::POS_END);