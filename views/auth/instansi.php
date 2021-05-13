<?php
use yii\helpers\Url;
$this->title="Instansi";
?>
<div class="container-fluid">
	<div class="col-md-4 col-md-offset-4">
		<br><br>
		<div class="row">
			<div class="col-md-12 text-center">
				<center><a href="<?php echo Url::to(['auth/index']); ?>"><img src="<?php echo Url::base() ?>/img/logo_rsud.jpg" width='30%'></a></center><br>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<a href="<?php echo Url::to(['auth/instansi-peserta-login']); ?>" class="btn btn-lg btn-block btn-default btn-login">
					<img src="<?php echo Url::base() ?>/img/user.jpg" width="100%"><br>
					Peserta
				</a>
				<p style="text-align:center;"><small>Login untuk peserta dari suatu instansi/perusahaan</small></p>
			</div>
			<div class="col-md-6">
				<a href="<?php echo Url::to(['auth/instansi-login']); ?>" class="btn btn-lg btn-block btn-default btn-login">
					<img src="<?php echo Url::base() ?>/img/company.jpg" width="100%"><br>
					Perusahaan / Instansi
				</a>
				<p style="text-align:center;"><small>Pendaftaran untuk perwakilan dari instansi/perusahaan.</small></p>
			</div>
		</div>
	</div>
</div>