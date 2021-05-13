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
small{
    font-size:11px !important;
}
p{
    text-align:justify;
}
</style>
<table border="1" style="border-collapse:collapse;">
    <tr>
        <td width="50%">
            <img src="<?php echo Url::base() ?>/img/header_rsud.jpg" width='50%' style='padding-bottom: 10px; '>
        </td>
        <td>
            <table style="font-size:12px;">
                <tbody>
                    <tr>
                        <td>Nama Pasien</td>
                        <td>:</td>
                        <td><?php echo $u['u_nama_depan']; ?></td>
                    </tr>
                    <tr>
                        <td>Nomor Rekam Medis</td>
                        <td>:</td>
                        <td><?php echo $u['u_rm']; ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>:</td>
                        <td><?php echo date('d-m-Y',strtotime($u['u_tgl_lahir'])); ?></td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td><?php echo $u['u_jkel']=='L' ? 'Laki-laki' : 'Perempuan' ?></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<div class="subheader">
	<h4 style=" margin-top: 5px; margin-bottom: 0px;">PERSETUJUAN PELEPASAN INFORMASI MEDIS</h4>
    <small style="font-size:11px;"><i>APPROVAL OF DISCLOSURE OF MEDICAL INFORMATION</i></small>
</div>
<br>
<p>
    Saya yang bertanda tangan dibawah ini:<br>
    <small><i>I, the undersigned below:</i></small>
</p>
<table width="100%" border='0' style="text-align:left;">
    <tbody>
        <tr>
            <td width="30%">Nama<br><small><i>Name</i></small></td>
            <td valign="top" width="3%">:</td>
            <td valign="top"><?php echo $u['u_nama_depan']; ?></td>
        </tr>
        <tr>
            <td>Tempat/tanggal lahir<br><small><i>Date of birth</i></small></td>
            <td>:</td>
            <td><?php echo $u['u_tmpt_lahir'].' / '.date('d-m-Y',strtotime($u['u_tgl_lahir'])) ?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin<br><small><i>Gender</i></small></td>
            <td>:</td>
            <td><?php echo $u['u_jkel']=='L' ? 'Laki-laki' : 'Perempuan' ?></td>
        </tr>
        <tr>
            <td>Alamat<br><small><i>Address</i></small></td>
            <td>:</td>
            <td><?php echo $u['u_alamat'] ?></td>
        </tr>
    </tbody>
</table>
<p>
Dengan ini saya menyetujui hasil pemeriksaan kesehatan yang telah saya lakukan untuk mengetahui kondisi kesehatan saya, disampaikan kepada:
</p>
<table width="100%" border='0' style="text-align:left;">
    <tbody>
        <tr>
            <td width="30%">Nama Instansi/Perusahaan<br><small><i>In Institution/Company Name</i></small></td>
            <td valign="top" width="3%">:</td>
            <td valign="top">
            <?php 
                if($u['jadwalperiksa']!=NULL){
                    if($u['jadwalperiksa']['permintaan']!=NULL){
                        if($u['jadwalperiksa']['permintaan']['user']!=NULL){
                            echo $u['jadwalperiksa']['permintaan']['user']['u_nama_depan'];
                        }
                    }
                } 
            ?>
            </td>
        </tr>
        <tr>
            <td>Alamat Instansi/Perusahaan<br><small><i>In Institution/Company Adress</i></small></td>
            <td>:</td>
            <td valign="top">
            <?php 
                if($u['jadwalperiksa']!=NULL){
                    if($u['jadwalperiksa']['permintaan']!=NULL){
                        if($u['jadwalperiksa']['permintaan']['user']!=NULL){
                            echo $u['jadwalperiksa']['permintaan']['user']['u_alamat'];
                        }
                    }
                } 
            ?>
            </td>
        </tr>
        <tr>
            <td>Untuk Keperluan<br><small><i>for the purpose of</i></small></td>
            <td>:</td>
            <td valign="top">
            <?php 
                if($u['jadwalperiksa']!=NULL){
                    if($u['jadwalperiksa']['permintaan']!=NULL){
                        if($u['jadwalperiksa']['permintaan']['user']!=NULL){
                            echo $u['jadwalperiksa']['permintaan']['up_nama'];
                        }
                    }
                } 
            ?>
            </td>
        </tr>
    </tbody>
</table>
<p>
Demikianlah surat persetujuan ini saya buat, agar dapat dipergunakan sebagai mana mestinya.<br>
<small><i>Thus I made this agreement letter, so that it can be used as it should.</i></small>
</p>
<br>
<table width="100%" border='0'>
    <tr>
        <td width="30%" align="center">
            Saksi II<br><small><i>witness II</i></small>
            <br><br><br>
            <br><br><br>
            <br><br>
            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            )
        </td>
        <td width="30%" align="center">
            Saksi I<br><small><i>witness I</i></small>
            <br><br><br>
            <br><br><br>
            <br><br>
            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            )
        </td>
        <td width="30%" align="center">
            Pekanbaru, <?php echo date('d-m-Y') ?>
            <br>
            Pemberi Persetujuan<br><small><i>approver</i></small>
            <br><br><br>
            <br><br><br>
            <br>
            (<?php echo $u['u_nama_depan']; ?>)
        </td>
    </tr>
</table>