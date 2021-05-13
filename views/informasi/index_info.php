<?php
use yii\helpers\Url;
$this->title = 'Informasi Pendaftaran';
?>
<h2><strong><?= $this->title ?></strong></h2>
<div class="row">
    <div class="col-md-12"><br><strong style="color:#9e9e9e;">Alur Pendaftaran :</strong><img src='<?php echo Url::base(); ?>/img/alur.jpg' width='100%' /></div>
</div>
<br>
<strong style="color:#9e9e9e;">Informasi :</strong>
<ul class="list-group">
    <?php
    if(count($data)>0){
        $no=1;
        foreach($data as $d){
            ?><li class="list-group-item"><?php echo $no.') '.$d['info_isi']; ?></li><?php
            $no++;
        }
    }else{
        ?><li>Informasi tidak tersedia</li><?php
    }
    ?>
</ul>