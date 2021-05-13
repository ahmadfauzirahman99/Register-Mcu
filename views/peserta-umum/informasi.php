<?php
use yii\helpers\Url;
$this->title="Informasi Pendaftaran Peserta Umum";
?>
<h4><?php echo $this->title; ?></h4>
<ol>
<?php
if(count($informasi)>0){
    foreach($informasi as $i){
        ?><li><?php echo $i['i_info']; ?></li><?php
    }
}
?>
</ol>
<iframe style="width:100%; height:800px;" src="<?php echo Url::to(['/file/pedoman-pemeriksaan','q'=>1]) ?>"></iframe>