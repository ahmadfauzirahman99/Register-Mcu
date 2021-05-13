<?php
use app\models\User;
use app\widgets\App;
use yii\helpers\Url;
$this->registerCss("
    .progress{
        height:25px !important;
    }
    .progress .active{
        background-color:#318431;
        font-weight:bolder;
    }
    .progress .inactive{
        background-color:#b5823b;
        font-weight:bolder;
    }
    .progress-bar{
        padding:3px;
    }
    .progress a:hover,
    .progress a:active{
        text-decoration:none;
        color:#000;
    }
");
$no=1;
$width=20;
$user=Yii::$app->user->identity;
if($user!=NULL){
    if($user['u_jenis_mcu_id']==1){
        $width=16.66;
    }
}
?>
<div class="progress">
    <a href="<?php echo Url::to(['biodata']); ?>" class="<?php echo Yii::$app->controller->action->id=="biodata" ? ( $user!=NULL ? ( $user['u_biodata_finish_at']==NULL ? 'inactive' : 'active' ) : 'inactive' ) : "" ?> progress-bar <?php echo $user!=NULL ? ( $user['u_biodata_finish_at']==NULL ? 'progress-bar-warning' : 'progress-bar-success' ) : 'progress-bar-warning' ?> progress-daftar" style="width: <?php echo $width; ?>%" title="<?php echo $user!=NULL ? ($user['u_biodata_finish_at']!=NULL ? 'selesai' : 'belum selesai') : '' ?>">
        DAFTAR <span class="status-icon"><?php echo $user!=NULL ? ($user['u_biodata_finish_at']!=NULL ? '<i class="fa fa-check-circle"></i>' : '') : '' ?></span>
    </a>
    <a href="<?php echo Url::to(['berkas']); ?>" class="<?php echo Yii::$app->controller->action->id=="berkas" ? ( $user!=NULL ? ($user['u_berkas_finish_at']==NULL ? 'inactive' : 'active') : 'inactive' ) : "" ?> progress-bar <?php echo $user!=NULL ? ($user['u_berkas_finish_at']==NULL ? 'progress-bar-warning' : 'progress-bar-success') : 'progress-bar-warning' ?> progress-berkas" style="width: <?php echo $width; ?>%" title="<?php echo $user!=NULL ? ($user['u_berkas_finish_at']!=NULL ? 'selesai' : 'belum selesai') : '' ?>">
        UPLOAD BERKAS <span class="status-icon"><?php echo $user!=NULL ? ( $user['u_berkas_finish_at']!=NULL ? '<i class="fa fa-check-circle"></i>' : '' ) : '' ?></span>
    </a>
    <a href="<?php echo Url::to(['kuisioner-sosial']); ?>" class="<?php echo Yii::$app->controller->action->id=="kuisioner-sosial" ? ( $user!=NULL ? ($user['u_kuisioner1_finish_at']==NULL ? 'inactive' : 'active') : 'inactive' ) : "" ?> progress-bar <?php echo $user!=NULL ? ($user['u_kuisioner1_finish_at']==NULL ? 'progress-bar-warning' : 'progress-bar-success') : 'progress-bar-warning' ?> progress-kuisioner1" style="width: <?php echo $width; ?>%" title="<?php echo $user!=NULL ? ($user['u_kuisioner1_finish_at']!=NULL ? 'selesai' : 'belum selesai') : '' ?>">
        KUISIONER SOSIAL <span class="status-icon"><?php echo $user!=NULL ? ( $user['u_kuisioner1_finish_at']!=NULL ? '<i class="fa fa-check-circle"></i>' : '' ) : '' ?></span>
    </a>
    <a href="<?php echo Url::to(['kuisioner-penyakit']); ?>" class="<?php echo Yii::$app->controller->action->id=="kuisioner-penyakit" ? ( $user!=NULL ? ($user['u_kuisioner2_finish_at']==NULL ? 'inactive' : 'active') : 'inactive' ) : "" ?> progress-bar <?php echo $user!=NULL ? ($user['u_kuisioner2_finish_at']==NULL ? 'progress-bar-warning' : 'progress-bar-success') : 'progress-bar-warning' ?> progress-kuisioner2" style="width: <?php echo $width; ?>%" title="<?php echo $user!=NULL ? ($user['u_kuisioner2_finish_at']!=NULL ? 'selesai' : 'belum selesai') : '' ?>">
        KUISIONER PENYAKIT <span class="status-icon"><?php echo $user!=NULL ? ( $user['u_kuisioner2_finish_at']!=NULL ? '<i class="fa fa-check-circle"></i>' : '' ) : '' ?></span>
    </a>
   <?php
   if($user!=NULL){
        if($user['u_jenis_mcu_id']==1){
            ?>
            <a href="<?php echo Url::to(['kuisioner-anamnesa']); ?>" class="<?php echo Yii::$app->controller->action->id=="kuisioner-anamnesa" ? ( $user!=NULL ? ($user['u_kuisioner3_finish_at']==NULL ? 'inactive' : 'active') : 'inactive' ) : "" ?> progress-bar <?php echo $user!=NULL ? ($user['u_kuisioner3_finish_at']==NULL ? 'progress-bar-warning' : 'progress-bar-success') : 'progress-bar-warning' ?> progress-kuisioner3" style="width: <?php echo $width; ?>%" title="<?php echo $user!=NULL ? ($user['u_kuisioner3_finish_at']!=NULL ? 'selesai' : 'belum selesai') : '' ?>">
                KUISIONER ANAMNESA <span class="status-icon"><?php echo $user!=NULL ? ( $user['u_kuisioner3_finish_at']!=NULL ? '<i class="fa fa-check-circle"></i>' : '' ) : '' ?></span>
            </a>
            <?php
        }
   }
   ?>
   <a href="<?php echo Url::to(['selesai']); ?>" class="<?php echo Yii::$app->controller->action->id=="selesai" ? ( $user!=NULL ? ($user['u_finish_at']==NULL ? 'inactive' : 'active') : 'inactive' ) : "" ?> progress-bar <?php echo $user!=NULL ? ($user['u_finish_at']==NULL ? 'progress-bar-warning' : 'progress-bar-success') : 'progress-bar-warning progress-finish' ?>" style="width: <?php echo $width; ?>%">
        SELESAI <span class="status-icon"><?php echo $user!=NULL ? ( $user['u_finish_at']!=NULL ? '<i class="fa fa-check-circle"></i>' : '' ) : '' ?></span>
   </a>
</div>