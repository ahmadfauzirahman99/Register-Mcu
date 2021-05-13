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
		$('.perawat option:first').prop('selected',true);
		el.val('').hide('slow');
	}
});
");
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Form Laporan</h4>
        </div>
        <?php $form = ActiveForm::begin(['action'=>Url::to(['report']),'options'=>['target'=>'_blank']]); ?>
        <div class="modal-body">
            <div class="form-group">
                <label for="recipient-name" class="control-label">Status</label>
                <select name="status" class="form-control">
                    <option value="0">Tidak Memenuhi Syarat</option>
                    <option value="1">Memenuhi Syarat</option>
                    <option value="2">Dipertimbangkan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label">Formasi</label>
                <select name="formasi" id="formasi" class="form-control">
                    <?php
                    if(count($formasi)>0){
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
					<option value="s">Seleksi</option>
					<option value="p">Prestasi</option>
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