<?php
use app\widgets\App;
use yii\helpers\Url;
$tmp=!Yii::$app->user->isGuest ? Yii::$app->user->identity : NULL;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <ul class="timeline timeline-registrasi" id="timeline">
                <li class="li biodata <?php echo $tmp!=NULL ? ($tmp->u_biodata_finish_at!=NULL ? "complete" : "") : NULL ?>">
                    <a href="<?php echo Url::to(['biodata']) ?>" title="">
                        <div class="status">
                            <h4 class="<?php echo Yii::$app->controller->action->id=="biodata" ? 'active' : '' ?>">Biodata</h4>
                        </div>
                    </a>
                </li>
                <li class="li berkas <?php echo $tmp!=NULL ? ($tmp->u_berkas_finish_at!=NULL ? "complete" : "") : NULL ?>">
                    <a href="<?php echo Url::to(['berkas']) ?>" title="">
                        <div class="status">
                            <h4 class="<?php echo Yii::$app->controller->action->id=="berkas" ? 'active' : '' ?>">Upload Berkas</h4>
                        </div>
                    </a>
                </li>
                <li class="li kuisioner <?php echo $tmp!=NULL ? ($tmp->u_kuisioner1_finish_at!=NULL && $tmp->u_kuisioner2_finish_at!=NULL ? "complete" : "") : NULL ?>">
                    <a href="<?php echo Url::to(['kuisioner']) ?>" title="">
                        <div class="status">
                            <h4 class="<?php echo Yii::$app->controller->action->id=="kuisioner" ? 'active' : '' ?>">Kuisioner</h4>
                        </div>
                    </a>
                </li>
                <li class="li <?php echo $tmp!=NULL ? ($tmp->u_finish_at!=NULL ? "complete" : "") : NULL ?>">
                    <a href="<?php echo Url::to(['selesai']) ?>" title="">
                        <div class="status">
                            <h4 class="<?php echo Yii::$app->controller->action->id=="selesai" ? 'active' : '' ?>"><?php echo $tmp!=NULL ? ($tmp->u_finish_at==NULL ? "Selesai" : "Bukti Pendaftaran") : NULL ?></h4>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>