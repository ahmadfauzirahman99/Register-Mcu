<?php
use yii\helpers\Url;
$this->title = 'Informasi Pendaftaran';
?>
<h2><strong><?= $this->title ?></strong></h2>
<strong style="color:#9e9e9e;">Informasi :</strong>
<ul class="list-group">
    <?php
    $no=0;
    if(count($data)>0){
        foreach($data as $d){
            $no++;
            ?><li class="list-group-item"><?php echo $no.') '.$d['i_info']; ?></li><?php
            
        }
    }else{
        ?><li>Informasi tidak tersedia</li><?php
    }
    ?>
</ul>
<iframe style="width:100%; height:800px;" src="<?php echo Url::to(['/file/pedoman-pemeriksaan','q'=>2]) ?>"></iframe>
Jika panduan kegiatan pemeriksaan kesehatan diatas tidak tampil, silahkan klik link <a href='<?php echo Url::to(['/file/pedoman-pemeriksaan']) ?>' target='_blank'>ini</a>
<?php
if(Yii::$app->user->isGuest){
    ?><a href="<?php echo Url::to(['login']) ?>" class="btn btn-success"><i class="glyphicon glyphicon-log-in"></i> Login</a><?php
}
