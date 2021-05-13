<?php
use app\widgets\App;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = 'Login Instansi';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $('#".$login->formName()."').on('beforeSubmit',function(e){
        e.preventDefault();
        var btn=$('.btn-submit');
        var htm=btn.html();
        setBtnLoading(btn,'Loading...');
        $.ajax({
            url:'".Url::to(['instansi-login-do'])."',
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
    $('.btn-show-login').click(function(e){
        e.preventDefault();
        $('#".$login->formName()."').show('slow');
        $('#".$daftar->formName()."').hide('slow');
    });
    $('.btn-show-daftar').click(function(e){
        e.preventDefault();
        $('#".$login->formName()."').hide('slow');
        $('#".$daftar->formName()."').show('slow');
    });
");
if(!App::isRegClose()){
    $this->registerJs("
    $('#".$daftar->formName()."').on('beforeSubmit',function(e){
        e.preventDefault();
        var form = $(this);
        var btn=$('.btn-submit');
        var htm=btn.html();
        setBtnLoading(btn,'Loading...');
        $.ajax({
            url:'".Url::to(['instansi-daftar'])."',
            type:'post',
            dataType:'json',
            data:form.serialize(),
            success:function(result){
                if(result.status){
                    successMsg(result.msg);
                    $('.btn-show-login').click();
                    form.find('.form-control').val('');
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
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4" style="border:1px solid wred;">
            <div class="site-login">
                <center><a href="<?php echo Url::to(['/']); ?>"><img src="<?php echo Url::base() ?>/img/logo_rsud.jpg" width='30%'></a></center><br>
                <?php $form = ActiveForm::begin(['id'=>$login->formName(),'options'=>['class'=>'well'] ]); ?>
                    <p>Login sebagai perwakilan instansi/perusahaan: </p>
                    <?= $form->field($login, 'username')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($login, 'password')->passwordInput() ?>
                    <?php echo $form->field($login, 'captcha')->widget(\yii\captcha\Captcha::classname(), [
                        'captchaAction' => '/auth/captcha_login',
                        'options'=>[
                            'autocomplete'=>'off',
                            'class'=>'form-control',
                            'placeholder'=>'Ketik Captcha',
                            'style'=>'padding-left:10px; padding-right:10px;'
                        ],
                        'imageOptions'=>[
                            'foreColor'=>'red'
                        ],
                    ])->hint('klik gambar captcha untuk generate ulang') ?>
                    <button type="submit" class="btn btn-success btn-block btn-submit"><i class="glyphicon glyphicon-log-in"></i> Login</button>
                    perusahaan anda belum memiliki akun ? silahkan <a href='#' class='btn-show-daftar'>daftar</a>
                <?php ActiveForm::end(); ?>
                <?php $form = ActiveForm::begin(['id'=>$daftar->formName(),'options'=>['class'=>'well','style'=>'display:none;'] ]); 
                if(!App::isRegClose()){
                    ?>
                    <p>Buat akun perusahaan/instansi anda : </p>
                    <?= $form->field($daftar, 'u_nama_depan')->textInput()->label('Nama Instansi/Perusahaan'); ?>
                    <?= $form->field($daftar, 'u_alamat')->textInput()->label('Alamat Instansi/Perusahaan'); ?>
                    <div class="row">
                        <div class="col-md-6"><?= $form->field($daftar, 'username')->textInput()->label('Username'); ?></div>
                        <div class="col-md-6"><?= $form->field($daftar, 'u_password')->passwordInput()->label('Password'); ?></div>
                    </div>
                    <?php echo $form->field($daftar, 'captcha')->widget(\yii\captcha\Captcha::classname(), [
                        'captchaAction' => '/auth/captcha_daftar',
                        'options'=>[
                            'autocomplete'=>'off',
                            'class'=>'form-control',
                            'placeholder'=>'Ketik Captcha',
                            'style'=>'padding-left:10px; padding-right:10px;'
                        ],
                        'imageOptions'=>[
                            'foreColor'=>'red'
                        ],
                    ])->hint('klik gambar captcha untuk generate ulang') ?>
                    <button type="submit" class="btn btn-success btn-block btn-submit"><i class="fa fa-save"></i> Buat Akun</button>
                    anda sudah memiliki akun ? silahkan <a href='#' class='btn-show-login'>login</a>
                <?php 
                }else{
                    ?>
                    <div class="alert alert-danger">Pendaftaran instansi/perusahaan untuk sementara ditutup</div>
                    <?php
                }
                ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>