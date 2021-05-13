<?php
use yii\helpers\Url;
$this->title="Informasi/Tata Cara Pendaftaran";
?>
<h3><?php echo $this->title; ?></h3>
<ol type='1'>
	<?php
	if(count($info)>0){
		foreach($info as $i){
			?><li><?php echo $i['i_info']; ?></li><?php
		}
	}else{
		?><li>Informasi tidak tersedia</li><?php
	}
	?>
</ol>
<iframe style="width:100%; height:800px;" src="<?php echo Url::to(['/file/pedoman-pemeriksaan','q'=>2]) ?>"></iframe>
<?php
if(count($pekerjaan)>0){
	?>
	<h4>Kode Pekerjaan</h4>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th style="text-align:center;">Kode</th>
				<th style="text-align:center;">Pekerjaan</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($pekerjaan as $p){
				?>
				<tr>
					<td align="center"><?php echo $p['Nomor']; ?></td>
					<td align="center"><?php echo $p['PerkerjaanJabatan']; ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
}
if(count($paket)>0){
	?>
	<h4>Kode Paket</h4>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th style="text-align:center;">Kode</th>
				<th style="text-align:center;">Nama Paket</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($paket as $p){
				?>
				<tr>
					<td align="center"><?php echo $p['kode']; ?></td>
					<td align="center"><?php echo $p['nama']; ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
}