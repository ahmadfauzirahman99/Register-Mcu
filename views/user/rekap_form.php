<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->registerJs("
$('#formasi').change(function(e){
	var id = $(this).val();
	var el = $('.perawat');
	if(id==9){
		el.show('slow');
	}else{
		el.hide('slow');
	}
});
");
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Form Rekapitulasi</h4>
        </div>
        <?php $form = ActiveForm::begin(['action'=>Url::to(['rekap-all']),'options'=>['target'=>'_blank']]); ?>
        <div class="modal-body">
            <div class="form-group">
                <label for="message-text" class="control-label">Formasi</label>
                <select name="formasi" id="formasi" class="form-control">
                    <?php
                    if(count($formasi)>0){
						?><option value=""></option><?php
                        foreach($formasi as $f){
                            ?><option value="<?php echo $f['id']; ?>"><?php echo $f['nama']; ?></option><?php
                        }
                    }
                    ?>
                </select>
            </div>
			<div class="form-group perawat" style="display:none;">
				<label>Jalur Perawat</label>
				<select name="perawat" class="form-control">
					<option value=""></option>
					<option value="1">Seleksi</option>
					<option value="2">Prestasi</option>
				</select>
			</div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
  </div>