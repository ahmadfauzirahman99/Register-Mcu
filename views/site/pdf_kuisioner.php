<?php
use yii\helpers\Url;
use app\widgets\App;
use app\models\Kuisioner;
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
	font-size:13px;
	padding:3px;
    text-align:left;
} 
small{
    font-size:11px !important;
    text-decoration:italic;
}
p{
    text-align:justify;
}
.tb{
    border-collapse:collapse;
}
.tb thead tr th,
.tb tbody tr td{
    padding:3px;
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
                        <td><?php echo $u['u_rm'] ?></td>
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
	<h4 style=" margin-top: 5px; margin-bottom: 0px;">FORM KUISIONER MEDIS</h4>
</div>
<br>
<div class="title">
    <b>DATA PRIBADI</b><br><small><i>PERSONAL DETAILS</i></small>
</div>
<br>
<table class="tb" width="100%" border='1'>
    <tr>
        <td colspan='2'>Nama Lengkap : <?php echo $u['u_nama_depan']; ?></td>
    </tr>
</table>
<table class="tb" width="100%" border='1'>
    <tr>
        <td width="70%">Alamat : <?php echo $u['u_alamat']; ?></td>
        <td>No. Telp : <?php echo $u['u_no_hp']; ?></td>
    </tr>
</table>
<table class="tb" width="100%" border='1'>
    <tr>
        <td width="70%">Tanggal Lahir : <?php echo date('d-m-Y',strtotime($u['u_tgl_lahir'])); ?></td>
        <td>Status Perkawinan : <?php 
        if($u['u_status_nikah']=="K"){
            echo "Kawin";
        }elseif($u['u_status_nikah']=="T"){
            echo "Belum Kawin";
        }elseif($u['u_status_nikah']=="J"){
            echo "Janda";
        }elseif($u['u_status_nikah']=="D"){
            echo "Duda";
        }
        ?></td>
    </tr>
</table>
<table class="tb" width="100%" border='1'>
    <tr>
        <td>Jabatan Pekerjaan : <?php echo $u['u_jabatan_pekerjaan']; ?></td>
    </tr>
</table>
<table class="tb" width="100%" border='1'>
    <tr>
        <td>Anggota Tim Penanganan Keadaan Darurat : <?php echo $u['u_anggota_darurat']==1 ? 'Ya' : 'Tidak'; ?>
        <?php echo "<br>Alasan : ".($u['u_anggota_darurat']==1 ? $u['u_anggota_darurat_ket'] : '')  ?>
        </td>
    </tr>
</table>
<table class="tb" width="100%" border='1'>
    <tr>
        <td>Tanggal Medical Check Up : <?php echo !empty($u['u_tgl_terakhir_mcu']) ? date('d-m-Y',strtotime($u['u_tgl_terakhir_mcu'])) : ''; ?></td>
    </tr>
</table>
<table class="tb" width="100%" border='1'>
    <tr>
        <td width="50%">Nama Dokter Pemeriksa :</td>
        <td><?php echo $u['u_dokter']; ?></td>
    </tr>
    <tr>
        <td>Alamat Dokter Pemeriksa :</td>
        <td><?php echo $u['u_alamat_dokter']; ?></td>
    </tr>
</table>
<br>
<?php $no=0; ?>
<table class="tb" width="100%" border="1">
    <tr>
        <td align="center" colspan="6">
            <b>PEKERJAAN/PROFESI<br>
            <small>Job / Profesion</small></b>
        </td>
    </tr>
    <?php
    if($u['kuisionerbiodata']['ukb_krj_sebelum']!=NULL){
        ?>
        <tr>
            <td><?php echo ++$no; ?>. Sebelumnya</td>
            <td width="3%" align="center">:</td>
            <td><?php echo $u['kuisionerbiodata']['ukb_krj_sebelum']; ?></td>
            <td>Perusahaan</td>
            <td width="3%" align="center">:</td>
            <td><?php echo $u['kuisionerbiodata']['ukb_krj_sebelum_perusahaan']; ?></td>
        </tr>
        <?php
    }
    if($u['kuisionerbiodata']['ukb_krj_skrg']!=NULL){
        ?>
        <tr>
            <td><?php echo ++$no; ?>. Sekarang</td>
            <td width="3%" align="center">:</td>
            <td><?php echo $u['kuisionerbiodata']['ukb_krj_skrg']; ?></td>
            <td>Perusahaan</td>
            <td width="3%" align="center">:</td>
            <td><?php echo $u['kuisionerbiodata']['ukb_krj_skrg_perusahaan']; ?></td>
        </tr>
        <?php
    }
    if($u['kuisionerbiodata']['ukb_krj_dituju']!=NULL){
        ?>
        <tr>
            <td><?php echo ++$no; ?>. Yang Dituju (diLamar)</td>
            <td width="3%" align="center">:</td>
            <td><?php echo $u['kuisionerbiodata']['ukb_krj_dituju']; ?></td>
            <td>Perusahaan</td>
            <td width="3%" align="center">:</td>
            <td><?php echo $u['kuisionerbiodata']['ukb_krj_dituju_perusahaan']; ?></td>
        </tr>
        <?php
    }
    if($no==0){
        ?>
        <tr>
            <td colspan="6">Pekerjaan Tidak Tersedia</td>
        </tr>
        <?php
    }
    if($no>0){
        ?>
        <tr>
            <td align="center" colspan="6"><b>Defenisi</b></td>
        </tr>
        <tr>
            <td colspan="6">
                <table class='table table-bordered'>
                    <tr>
                        <td>Uraian Tugas</td>
                        <td>:</td>
                        <td>Uraian fungsi dan tanggungjawab dalam kegiatan pekerjaan</td>
                    </tr>
                    <tr>
                        <td>Target Kerja</td>
                        <td>:</td>
                        <td>Sasaran yang telah ditetapkan untuk dicapai dalam suatu pekerjaan</td>
                    </tr>
                    <tr>
                        <td>Cara Kerja</td>
                        <td>:</td>
                        <td>Tahapan yang dilakukan sehingga tercapai tujuan pekerjaan</td>
                    </tr>
                    <tr>
                        <td>Alat Kerja</td>
                        <td>:</td>
                        <td>Benda yang digunakan untuk mengerjakan sesuatu untuk mempermudah pekerjaan</td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<?php
if($no>0):
?>
<table class="tb" width="100%" border="1">
    <thead>
        <tr>
            <th>URAIAN</th>
            <th>PEKERJAAN UTAMA</th>
            <th>PEKERJAAN TAMBAHAN</th>
        </tr>
    </thead>
    <tbody>
        <?php if($u['kuisionerbiodata']['ukb_krj_sebelum']!=NULL): ?>
            <tr>
                <td colspan="3" align="center">RIWAYAT PEKERJAAN SEBELUMNYA</td>
            </tr>
            <tr>
                <td align="left">Uraian Tugas</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_sblm_utama_uraian'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_sblm_tambah_uraian'] ?></td>
            </tr>
            <tr>
                <td align="left">Target Kerja</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_sblm_utama_target'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_sblm_tambah_target'] ?></td>
            </tr>
            <tr>
                <td align="left">Cara Kerja</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_sblm_utama_cara'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_sblm_tambah_cara'] ?></td>
            </tr>
            <tr>
                <td align="left">Alat Kerja</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_sblm_utama_alat'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_sblm_tambah_alat'] ?></td>
            </tr>
        <?php 
        endif; 
        if($u['kuisionerbiodata']['ukb_krj_skrg']!=NULL):
        ?>
            <tr>
                <td colspan="3" align="center">PEKERJAAN SEKARANG</td>
            </tr>
            <tr>
                <td align="left">Uraian Tugas</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_skrg_utama_uraian'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_skrg_tambah_uraian'] ?></td>
            </tr>
            <tr>
                <td align="left">Target Kerja</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_skrg_utama_target'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_skrg_tambah_target'] ?></td>
            </tr>
            <tr>
                <td align="left">Cara Kerja</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_skrg_utama_cara'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_skrg_tambah_cara'] ?></td>
            </tr>
            <tr>
                <td align="left">Alat Kerja</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_skrg_utama_alat'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_skrg_tambah_alat'] ?></td>
            </tr>
        <?php 
        endif; 
        if($u['kuisionerbiodata']['ukb_krj_dituju']!=NULL):
            ?>
            <tr>
                <td colspan="3" align="center">PEKERJAAN YANG DITUJU/DILAMAR</td>
            </tr>
            <tr>
                <td align="left">Uraian Tugas</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_dituju_utama_uraian'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_dituju_tambah_uraian'] ?></td>
            </tr>
            <tr>
                <td align="left">Target Kerja</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_dituju_utama_target'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_dituju_tambah_target'] ?></td>
            </tr>
            <tr>
                <td align="left">Cara Kerja</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_dituju_utama_cara'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_dituju_tambah_cara'] ?></td>
            </tr>
            <tr>
                <td align="left">Alat Kerja</td>
                <td><?php echo $u['kuisionerbiodata']['ukb_dituju_utama_alat'] ?></td>
                <td><?php echo $u['kuisionerbiodata']['ukb_dituju_tambah_alat'] ?></td>
            </tr>
        <?php
        endif;
        ?>
    </tbody>
</table>
<?php endif; ?>
<br>
<table class="tb" width="100%" border="1">
    <thead>
        <tr>
            <th>No</th>
            <th></th>
            <th width="10%">Pilihan</th>
            <th width='40%'>Jelaskan (Jika iya)</th>
        </tr>
    </thead>
    <tbody>
        <?php echo App::pdfRiwayatSosial($kuisioner_sosial); ?>
    </tbody>
</table>
<pagebreak />
<div class="title">
    <b>GENERAL MEDICAL QUESTIONNAIRE</b>
</div>
<?php
foreach($kategori_kuisioner as $kk){
    $data=Kuisioner::find()->where(['kk_id'=>$kk['kk_id']])->asArray()->all();
    ?>
    <div style="font-size:17px; font-weight:bolder; border-bottom:1px solid #000; margin-top:10px; margin-bottom:15px;"><?php echo $kk['kk_nama_indo']; ?></div>
    <strong><?php echo $kk['kk_ket_ind']; ?></strong><br>
    <small><i><?php echo strtolower($kk['kk_ket_eng']); ?></i></small>
    <table class="tb" width="100%" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th></th>
                <th width="10%">Pilihan</th>
                <th width='40%'>Jelaskan (Jika iya)</th>
            </tr>
        </thead>
        <tbody>
            <?php echo App::pdfRiwayatPenyakit($data); ?>
        </tbody>
    </table>
    <?php
}
?>
<br>
<table width="100%" border='0'>
    <tr>
        <td width="60%"></td>
        <td align="center">
            Pekanbaru, <?php echo date('d-m-Y'); ?>
            <br>
            Yang Membuat<br>
            <small>Signed By</small>
            <br><br><br><br><br>
            (<?php echo $u['u_nama_depan']; ?>)
        </td>
    </tr>
</table>

<br>
<div class="title">
    <b>KESIMPULAN DOKTER</b><br><small><i>EXAMINING PHYSICIAN’S COMMENTS</i></small>
</div>
<table width="100%" border="1" style="border-collapse:collapse;">
    <tr>
        <td>
            <br><br><br><br>
        </td>
    </tr>
</table>
<br>
<table width="100%" border="1" style="border-collapse:collapse;">
    <tr>
        <td width="50%" align="center">
            <b>DOKTER PEMERIKSA</b><br>
            <small><i>Full Name of Examining Physician</i></small>
            <br><br><br><br><br>
        </td>
        <td align="center">
            <b>TANDATANGAN PEMERIKSA</b><br>
            <small><i>Signed by Physician</i></small>
            <br><br><br><br><br>
        </td>
    </tr>
</table>