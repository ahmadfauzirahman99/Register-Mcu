<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\TinymceAsset;
TinymceAsset::register($this);
$this->registerJs("
var simpan=false;
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'".url::to(['save'])."',
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
    })
}).on('submit',function(e){
    e.preventDefault();
});
$('#mymodal').on('hidden.bs.modal', function (e) {
    if(simpan){
        $.pjax.reload({container: '#pjax-informasi', async: false});
    }
});
tinymce.remove('#informasi-i_info');
$('#informasi-i_info').parents('form').on('beforeValidate', function() { tinymce.triggerSave(); });
tinymce.init({
    selector: '#informasi-i_info',
    height: 500,
    plugins: [
        'advlist autolink link image lists charmap hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars media nonbreaking',
        'save table contextmenu directionality emoticons paste textcolor'
   ],
    style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ],
    toolbar:[
        'bullist numlist link image hr searchreplace media emoticons forecolor backcolor'
    ],
});
");
?>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Form Informasi</h4>
        </div>
        <?php $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']); ?>
        <div class="modal-body">
            <?php
            if(!$model->isNewRecord){
                ?><input type="hidden" name="id" value="<?php echo $model->i_id; ?>"><?php
            }
            ?>
            <?= $form->field($model, 'i_info')->textArea(['rows'=>5,'class'=>'form-control iinfo']) ?>
            
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'i_jenis')->dropDownList([ '1'=>'Informasi pendaftaran umum', '3'=>'Informasi pendaftaran instansi' ,'2'=>'Disclaimer']) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'i_status')->dropDownList(['1'=>'Aktif','0'=>'Tidak Aktif']) ?></div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>