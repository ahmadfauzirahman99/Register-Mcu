<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title="Login Peserta Instansi";
$this->registerJs("
$('#instansipesertaloginform-tgl_lahir').inputmask({
    mask:'99-99-9999'
});
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit-login');
    var htm=btn.html();
    setBtnLoading(btn,'Loading...');
    $.ajax({
        url:'".Url::to(['instansi-peserta-login-do'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                location.reload();
            }else{
                errorMsg(result.msg); 
            }
            resetBtnLoading(btn,htm);
        },
        error:function(xhr,status,error){
            resetBtnLoading(btn,htm);
            errorMsg(error);     
        }
    });
}).on('submit',function(e){
    e.preventDefault();
});
");
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4" style="padding-top:50px;">
            <center><a href="<?php echo Url::to(['auth/instansi']); ?>"><img src="<?php echo Url::base() ?>/img/logo_rsud.jpg" width='30%'></a></center><br>
            <div class="well">
                <p>Silahkan login</p>
                <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>
                    <?= $form->field($model, 'nik')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'tgl_lahir')->textInput()->hint('format : tanggal-bulan-tahun') ?>
                    <?php echo $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::classname(), [
                        'captchaAction' => '/site/captcha',
                        'options'=>[
                            'autocomplete'=>'off',
                            'class'=>'form-control',
                            'placeholder'=>'Ketik Captcha',
                            'style'=>'padding-left:10px; padding-right:10px;'
                        ],
                        'imageOptions'=>[
                            'foreColor'=>'red'
                        ],
                    ])->hint('klik captcha untuk generate ulang') ?>
                    <div class="row">
                        <div class="col-md-8" style="vertical-align:bottom;"></div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success btn-submit-login"><i class="glyphicon glyphicon-log-in"></i> Login</button>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>