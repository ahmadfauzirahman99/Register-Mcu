<?php
use yii\helpers\Url;
use app\widgets\App;
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
	<h4 style=" margin-top: 5px; margin-bottom: 0px;">KUISIONER ANAMNESA</h4>
</div>
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
        <?php
        if(count($kuisioner_cpns)>0){
            $no=1;
            foreach($kuisioner_cpns as $kc){
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $kc['kuisioner']['k_isi_indo']; ?></td>
                    <td><?php echo $kc['uk_ceklis']==1 ? "Iya" : "Tidak"; ?></td>
                    <td><?php echo $kc['uk_keterangan']; ?></td>
                </tr>
                <?php
                $no++;
            }
        }else{
            ?><tr><td colspan="4">Data tidak tersedia</td></tr><?php
        }
        ?>
    </tbody>
</table>