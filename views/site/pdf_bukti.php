<?php
use yii\helpers\Url;
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
</style>
<div class="header">
	<img src="<?php echo Url::base() ?>/img/header_rsud.jpg" width='50%' style='padding-bottom: 10px; '>
</div>
<div class="subheader">
	<h4 style=" margin-top: 5px; margin-bottom: 0px;">BUKTI PENDAFTARAN PESERTA</h4>
</div>
<br>
<table class="table">
    <tbody>
        <tr>
            <th>NIK</th>
            <td>:</td>
            <td><?php echo $user['u_nik']; ?></td>
        </tr>
        <tr>
            <th>No. Rekam Medis</th>
            <td>:</td>
            <td><?php echo $user['u_rm']; ?></td>
        </tr>
        <tr>
            <th>Nama Lengkap</th>
            <td>:</td>
            <td><?php echo $user['u_nama_depan']; ?></td>
        </tr>
        <tr>
            <th valign="top">Jadwal Tes Kesehatan</th>
            <td valign="top">:</td>
            <td>
				<?php 
				if($user['jadwalperiksa']!=NULL){
                    echo date('d-m-Y',strtotime($user['jadwalperiksa']['upj_tgl']));
                }
				?>
			</td>
        </tr>
        <tr>
            <th>Waktu Pelaksanaan</th>
            <td>:</td>
            <td>07:00 s/d selesai</td>
        </tr>
    </tbody>
</table>
<p style="text-align:justify;">
    Selamat, anda sudah terdaftar, harap simpan bukti pendaftaran ini untuk keperluan konfirmasi ke loket pendaftaran RSUD Arifin Achmad Provinsi Riau.
</p>
<br><br><br>