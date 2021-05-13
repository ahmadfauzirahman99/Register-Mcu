<?php
use yii\helpers\Url;
?>
<style>
.header{
	text-align:center;
}
.subheader{
	text-align: center; 
    font-size:15px;
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
	<h4 style=" margin-top: 5px; margin-bottom: 0px;">DAFTAR KEHADIRAN PESERTA<br><?php echo strtoupper($data['permintaan']['up_nama']) ?></h4>
	<small>Tanggal Pemeriksaan <?php echo date('d-m-Y',strtotime($data['upj_tgl'])); ?> </small>
</div>
<br>
<table class="table" border="1" width="100%" style="border-collapse:collapse;">
	<thead>
		<tr>
			<th width="5%">No</th>
			<th>NIK</th>
			<th>Nama Peserta</th>
			<th>Paket</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if(count($data['user'])>0){
			$no=1;
			foreach($data['user'] as $u){
				?>
				<tr>
					<td align="center"><?php echo $no; ?></td>
					<td align="center"><?php echo $u['u_nik']; ?></td>
					<td align="center"><?php echo $u['u_nama_depan']; ?></td>
					<td align="center"><?php echo $u['paket']!=NULL ? $u['paket']['nama'] : NULL; ?></td>
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