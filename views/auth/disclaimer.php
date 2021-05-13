<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Disclaimer Pendaftaran MCU</h4>
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
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        </div>
    </div>
</div>