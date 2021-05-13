<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin(['action'=>Url::to(['import']),'options'=>['target'=>'_blank','enctype'=>'multipart/form-data']]); ?>
	<div class="form-group">
		<label for="import">Import Excel</label>
		<input type="file" name="import" id="import">
	</div>
	<button type="submit">SUBMIT</button>
<?php ActiveForm::end(); ?>