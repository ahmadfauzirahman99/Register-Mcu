<?php
use yii\helpers\Url;
?>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Informasi Pendaftaran Instansi</h4>
        </div>
        <div class="modal-body">
            <ol>
            <?php
            if(count($informasi)>0){
                foreach($informasi as $i){
                    ?><li><?php echo $i['i_info']; ?></li><?php
                }
            }
            ?>
            </ol>
            <iframe style="width:100%; height:800px;" src="<?php echo Url::to(['/file/pedoman-pemeriksaan','q'=>2]) ?>"></iframe>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        </div>
    </div>
</div>