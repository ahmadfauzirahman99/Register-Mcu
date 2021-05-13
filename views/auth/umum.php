<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title="Pendaftaran Peserta Umum";
$this->registerJs("
var wrap_login=$('.wrap-login');
var wrap_daftar=$('.wrap-daftar');
$('.btn-daftar').click(function(e){
    e.preventDefault();
    wrap_login.hide('slow');
    wrap_daftar.show('slow');
});
$('.btn-login').click(function(e){
    e.preventDefault();
    wrap_login.show('slow');
    wrap_daftar.hide('slow');
});
$('#umumloginform-tgl_lahir,#userdaftar-ud_tgl_lahir').inputmask({
    mask:'99-99-9999'
});
$('#".$login->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit-login');
    var htm=btn.html();
    setBtnLoading(btn,'Loading...');
    $.ajax({
        url:'".Url::to(['umum-login'])."',
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
$('#".$daftar->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var form=$(this);
    var btn=$('.btn-submit-daftar');
    var htm=btn.html();
    setBtnLoading(btn,'Loading...');
    $.ajax({
        url:'".Url::to(['umum-daftar'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                $('.btn-login').click();
                form.find('input[type=\'text\']').val('');
                successMsg(result.msg);
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
$('.btn-disclaimer').click(function(e){
    e.preventDefault();
    var btn=$(this);
    var htm=btn.html();
    setBtnLoading(btn,'Loading...');
    $.post('".Url::to(['disclaimer'])."',function(result){
        resetBtnLoading(btn,htm);
        $('#mymodal').html(result).modal({show:true});
    });
});
");
?>
<div class="container">
    <div class="row wrap-login">
        <div class="col-md-4 col-md-offset-4" style="padding-top:50px;">
            <center><a href="<?php echo Url::to(['auth/index']); ?>"><img src="<?php echo Url::base() ?>/img/logo_rsud.jpg" width='30%'></a></center><br>
            <div class="well">
                <p>Silahkan login</p>
                <?php $form = ActiveForm::begin(['id'=>$login->formName()]); ?>
                    <?= $form->field($login, 'nik')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($login, 'tgl_lahir')->textInput()->hint('format : tanggal-bulan-tahun') ?>
                    <?php echo $form->field($login, 'captcha')->widget(\yii\captcha\Captcha::classname(), [
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
                    ])->hint('<small>klik gambar captcha untuk generate ulang captcha</small>') ?>
                    <div class="row">
                        <div class="col-md-8" style="vertical-align:bottom;">
                            <a href="#" class="btn-daftar" data-toggle="tooltip" title="klik ini"><small>Daftar jika belum pernah berobat di RSUD Arifin Achmad</small></a>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success btn-submit-login"><i class="glyphicon glyphicon-log-in"></i> Login</button>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="row wrap-daftar" style="display:none;">
        <div class="col-md-4 col-md-offset-4" style="padding-top:50px;">
            <center><a href="<?php echo Url::to(['auth/index']); ?>"><img src="<?php echo Url::base() ?>/img/logo_rsud.jpg" width='30%'></a></center><br>
            <div class="well">
                <p>Silahkan daftar jika belum pernah  peserta</p>
                <?php $form = ActiveForm::begin(['id'=>$daftar->formName()]); ?>
                    <?= $form->field($daftar,'ud_nik')->textInput(['autofocus' => true]) ?>  
                    <?= $form->field($daftar,'ud_nama')->textInput() ?>  
                    <?= $form->field($daftar,'ud_email')->textInput() ?>
                    <?= $form->field($daftar, 'ud_tgl_lahir')->textInput()->hint('format : tanggal-bulan-tahun') ?>
                    <?php echo $form->field($daftar, 'captcha')->widget(\yii\captcha\Captcha::classname(), [
                        'captchaAction' => '/site/captcha_daftar',
                        'options'=>[
                            'autocomplete'=>'off',
                            'class'=>'form-control',
                            'placeholder'=>'Ketik Captcha',
                            'style'=>'padding-left:10px; padding-right:10px;'
                        ],
                        'imageOptions'=>[
                            'foreColor'=>'red'
                        ],
                    ])->hint('<small>klik gambar captcha untuk generate ulang captcha</small>') ?>
                    <div class="row">
                        <div class="col-md-8" style="vertical-align:bottom;">
                            <small>Dengan mendaftar, anda menyetujui <a href="#" data-toggle="tooltip" title="klik untuk membaca disclaimer" class="btn-disclaimer text-danger"><strong>disclaimer dari kami</strong></a></small>
                        </div>
                        <div class="col-md-4" style="vertical-align:bottom;">
                            <button type="submit" class="btn btn-success btn-submit-daftar"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </div><br>
                    <a href="#" class="btn-login btn-block text-center" data-toggle="tooltip" title="klik ini">
                        <small>Login jika pernah berobat di RSUD Arifin Achmad</small>
                    </a>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>