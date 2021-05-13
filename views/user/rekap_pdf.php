<?php
use app\models\User;
use yii\helpers\Url;
?>
<style>
.header{
	text-align:center;
}
.subheader{
	text-align: center; 
	border-top: 1px solid #000;
}
.table thead tr th{
	font-size:12px;
	padding:3px;
}
.table tbody tr td{
	font-size:11px;
	padding:3px;
} 
</style>
<div class="header">
    <img src="<?php echo Url::base() ?>/img/header_rsud.jpg" width='50%' style='padding-bottom: 10px; '>
</div>
<div class="subheader">
    <h4 style="margin-top: 5px; margin-bottom: 10px;">REKAPITULASI SELEKSI ADMINISTRASI REKRUTMEN PEGAWAI NON PNS BLUD RSUD ARIFIN ACHMAD TAHUN 2020</h4>
</div>
<table class="table" border="1" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th width='4%'>No</th>
            <th>Formasi</th>
            <th>Jumlah Formasi</th>
			<th>Pendaftaran</th>
            <th>Selesai Pendaftaran</th>
            <th>Tidak Selesai Pendaftaran</th>
            <th>Memenuhi Syarat</th>
			<!--<th>Dipertimbangkan</th>-->
            <th>Tidak Memenuhi Syarat</th>
        </tr>
    </thead>
	<tbody>
		<?php
		if(count($data)>0){
			$no=1;
			$kuota=0;
			$memenuhi_syarat=0;
			$tidak_memenuhi_syarat=0;
			$dipertimbangkan=0;
			$daftar=0;
			$selesai_daftar=0;
			$tidak_selesai_daftar=0;
			foreach($data as $d){
				$kuota+=$d['kuota'];
				$memenuhi_syarat+=$d['memenuhi_syarat'];
				$tidak_memenuhi_syarat+=$d['tidak_memenuhi_syarat'];
				$dipertimbangkan+=$d['dipertimbangkan'];
				$daftar+=$d['daftar'];
				$selesai_daftar+=$d['selesai_daftar'];
				$tidak_selesai_daftar+=$d['tidak_selesai_daftar'];
				?>
				<tr>
					<td align="center"><?php echo $no; ?></td>
					<td><?php echo $d['formasi']; ?></td>
					<td align="center"><?php echo $d['kuota']; ?></td>
					<td align="center"><?php echo $d['daftar']; ?></td>
					<td align="center"><?php echo $d['selesai_daftar']; ?></td>
					<td align="center"><?php echo $d['tidak_selesai_daftar']; ?></td>
					<td align="center"><?php echo $d['memenuhi_syarat']; ?></td>
					<!--<td align="center"><?php echo $d['dipertimbangkan']; ?></td>-->
					<td align="center"><?php echo $d['tidak_memenuhi_syarat']; ?></td>
				</tr>
				<?php
				$no++;
			}
			?>
			<tr>
				<td colspan="2" align="center"><b>TOTAL</b></td>
				<td align="center"><?php echo $kuota; ?></td>
				<td align="center"><?php echo $daftar; ?></td>
				<td align="center"><?php echo $selesai_daftar; ?></td>
				<td align="center"><?php echo $tidak_selesai_daftar; ?></td>
				<td align="center"><?php echo $memenuhi_syarat; ?></td>
				<!--<td align="center"><?php echo $dipertimbangkan; ?></td>-->
				<td align="center"><?php echo $tidak_memenuhi_syarat; ?></td>
			</tr>
			<?php
		}else{
			echo "<tr><td colspan='9'>Data tidak tersedia</td></tr>";
		}
		?>
	</tbody>
</table>