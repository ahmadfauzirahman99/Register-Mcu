<?php
use yii\helpers\Url;
$this->title = 'Aplikasi MCU RSUD Arifin Achmad';
?>
<div class="container-fluid">
	<div class="col-md-4 col-md-offset-4">
		<br><br>
		<div class="row">
			<div class="col-md-12 text-center">
				<img src="<?php echo Url::base() ?>/img/logo_rsud.jpg" width='20%'>
				<h3><strong>RSUD ARIFIN ACHMAD PROVINSI RIAU</strong></h3>
				<p>Login/Daftar sebagai : </p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<a href="<?php echo Url::to(['auth/login-peserta']); ?>" class="btn btn-lg btn-block btn-default btn-login" data-toggle="tooltip" data-placement="bottom" title="login / daftar untuk peserta dari perusahaan atau umum">
					<img src="<?php echo Url::base() ?>/img/user.jpg" width="100%"><br>
					Peserta
				</a>
				<p style="text-align:center;"><small>Login untuk peserta perusahaan ataupun pribadi</small></p>
			</div>
			<div class="col-md-6">
				<a href="<?php echo Url::to(['auth/login-admin']); ?>" class="btn btn-lg btn-block btn-default btn-login" data-toggle="tooltip" data-placement="bottom" title="login peserta">
					<img src="<?php echo Url::base() ?>/img/company.jpg" width="100%"><br>
					Perusahaan / Instansi
				</a>
				<p style="text-align:center;"><small>Login untuk perwakilan dari instansi/perusahaan.</small></p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2 col-md-offset-5">
				<a href="#" class="btn btn-sm btn-default btn-block" title="click untuk detail informasi" data-toggle="modal" data-target='#modalinfo' style="border:0px;" role="button"><i class="fa fa-question-circle-o fa-2x"></i></a>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle"></i> Informasi</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<h3><strong><center>Prosedur Peserta</center></strong></h3>
						<img src="<?php echo Url::base() ?>/img/cara_peserta.jpg" width='100%'>
					</div>
					<div class="col-md-6">
						<h3><strong><center>Prosedur Instansi</center></strong></h3>
						<img src="<?php echo Url::base() ?>/img/cara_instansi.jpg" width='100%'>
					</div>
				</div>
				<br>
				<p>Jika mengalami kendala, silahkan chat via whatsapp ke no 085271888206</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
			</div>
		</div>
  	</div>
</div>