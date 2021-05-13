<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
$this->registerJs("
var simpan=false;
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'".url::to(['tindakan-save'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                simpan=true;
                successMsg(result.msg);
            }else{
                errorMsg(result.msg);
            }
            resetBtnLoading(btn,htm);
        },
        error:function(xhr,status,error){
            errorMsg(error);
            resetBtnLoading(btn,htm);
        }
    });
}).on('submit',function(e){
    e.preventDefault();
});
$('#mymodal').on('hidden.bs.modal', function (e) {
    if(simpan){
        location.reload();
    }
});
");
if(!$model->isNewRecord){
    $this->registerJs("
    $('.kode_tindakan').html('<option value=\'".$model->kode_tindakan."\'>".$model->nama_tindakan."</option>')
    ");
}
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Form <?php echo $model->isNewRecord ? 'Tambah' : 'Edit' ?> Tindakan</h4>
        </div>
        <?php $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']); ?>
        <div class="modal-body">
            <?php
            if(!$model->isNewRecord){
                ?><input type="hidden" name="update" value="<?php echo $model->id; ?>"><?php
            }
            ?>
            <?php echo $form->field($model, 'kode_paket',['template'=>'{input}','options' => ['tag'=>false]])->hiddenInput()->label(false);  ?>
            <?php echo $form->field($model,'kode_tindakan')->widget(Select2::className(),[
                'options' => ['placeholder' => 'ketik nama tindakan','class'=>'form-control kode_tindakan'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength'=>2,
                    'ajax' => [
                        'url' => Url::to(['tindakan-list']),
                        'type'=>'post',
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.termz}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('
                        function(data) {
                            return data.id+" / "+data.text;
                        }
                    '),
                    'templateSelection' => new JsExpression(
                    'function (data){
                        if(typeof data.id != "undefined" && data.id != ""){
                            return data.id+" / "+data.text;
                        }else{
                            return data.text;
                        }
                    }
                    '),
                ],
                'pluginEvents'=>[
                    "select2:select" => "function(obj){
                        console.log(obj);
                        var data=obj.params.data;
                        $('#pakettindakan-nama_tindakan').val(data.text);
                        $('#pakettindakan-harga').val(parseInt(data.harga));
                    }"
                ]
            ]);
            ?>
            <?= $form->field($model, 'nama_tindakan')->textInput() ?>
            <?= $form->field($model, 'harga')->textInput(['readonly'=>'readonly']) ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-paper-plane"></i> Simpan</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>