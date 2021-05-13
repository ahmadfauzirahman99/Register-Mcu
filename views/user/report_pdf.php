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
.table tbody tr td,
.table tbody tr th{
	font-size:11px;
	padding:3px;
} 
</style>
<div class="header">
    <img src="<?php echo Url::base() ?>/img/header_rsud.jpg" width='50%' style='padding-bottom: 10px; '>
</div>
<div class="subheader">
    <h4 style="margin-top: 5px; margin-bottom: 0px;">LAPORAN SELEKSI ADMINISTRASI REKRUTMEN PEGAWAI NON PNS BLUD RSUD ARIFIN ACHMAD TAHUN 2020</h4>
    <h4 style="font-weight: lighter; margin-top: 5px; margin-bottom: 0px;">
		<?php echo "<b>FORMASI : </b>".($data_formasi!=NULL ? strtoupper($data_formasi['nama']).' '.(!empty($perawat) ? ($perawat=='s' ? '- SELEKSI' : '- PRESTASI') : '' ) : '') ?>
		<?php echo ", <b>STATUS : </b>".($status=='0' ? "TIDAK MEMENUHI SYARAT" : ($status=='1' ? "MEMENUHI SYARAT" : "DIPERTIMBANGKAN")) ?>
		
	</h4>
</div>
<br>
<table class="table" border="1" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th width='4%'>No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Tempat / Tanggal Lahir</th>
            <th>Institusi Pendidikan</th>
            <th>IPK</th>
            <th>Verifikator</th>
            <?php echo $status=='0' || $status=='2' ? '<th>Keterangan</th>' : '' ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if(count($user)>0){
            $no=1;
            foreach($user as $u){
                ?>
                <tr>
                    <td align="center" style="vertical-align:top;"><?php echo $no; ?></td>
                    <td align="" style="vertical-align:top;"><?php echo $u['u_nik']; ?></td>
                    <td align="" style="vertical-align:top;"><?php echo $u['u_nama']; ?></td>
                    <td align="" style="vertical-align:top;"><?php echo $u['u_tmpt_lahir'].' / '.date('d-m-Y',strtotime($u['u_tgl_lahir'])); ?></td>
                    <td align="" style="vertical-align:top;"><?php echo $u['u_instansi']; ?></td>
                    <td align="center" style="vertical-align:top;"><?php echo $u['u_ipk']; ?></td>
                    <!--<td align="" style="vertical-align:top;"><?php echo $u['formasi']!=NULL ? $u['formasi']['f_nama_formasi'].( $u['u_formasi_id']==9 ? " ".($u['u_jalur_perawat']=='s' ? '( Seleksi )' : '( Prestasi )') : '' ) : '' ?></td>-->
                    <td  align="center" style="vertical-align:top;"><?php echo $u['u_verify_by']!=NULL ? User::find()->where(['u_id'=>$u['u_verify_by']])->asArray()->limit(1)->one()['u_nama'] : '' ?></td>
					<?php
					if($status=='0' || $status=='2'){
						?>
						<td style="vertical-align:top;">
							<?php
							if($u['u_lulus_reg']=='0'){
								echo (!empty($u['u_ket']) ? '<b>Alasan</b> : '.$u['u_ket'] : '');
							}elseif($u['u_lulus_reg']=='2'){
								echo (!empty($u['u_ket']) ? '<b>Alasan</b> : '.$u['u_ket'] : '');;
							}
							?>
						</td>
						<?php
					}
					?>
                </tr>
                <?php
                $no++;
            }
        }else{
            ?>
            <tr>
                <td colspan="8">Data tidak tersedia</td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<br><br>
<table border='0' width='100%'>
    <tr>
        <td width="50%"></td>
        <td align="center">
            Pekanbaru, <?php echo date('d/m/Y') ?>
            <br>
            <?php echo Yii::$app->params['jabatan']; ?>
            <br><br><br><br><br>
            <u><?php echo Yii::$app->params['pejabat']; ?></u><br>
            NIP : <?php echo Yii::$app->params['nip']; ?>
        </td>
    </tr>
</table>