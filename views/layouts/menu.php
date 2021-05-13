<?php
use app\widgets\App;
use yii\helpers\Url;
use yii\web\View;
$this->registerJs("
$('.btn-logout').click(function(e){
    e.preventDefault();
    if(confirm('Yakin Logout ?')){
        var btn=$(this);
        var htm=btn.html();
        setBtnLoading(btn,'Loading...');
        $.ajax({
            url:'".Url::to(['auth/logout'])."',
            type:'post',
            dataType:'json',
            success:function(result){
                if(result.status){
                    location.reload();
                }else{
                    errorMsg(result.msg);
                }
                resetBtnLoading(btn,htm);
            },
            error:function(xhr,statu,error){
                errorMsg(error);
                resetBtnLoading(btn,htm);
            }
        });
    }
    return false;
});
",View::POS_END);
if(App::isDokter() || App::isRm()){
    $this->registerJs("
    function checkPesertaBaru(){
        $.ajax({
            url:'".Url::to(['/admin/new-peserta-check'])."',
            type:'post',
            dataType:'json',
            success:function(result){
                var w = $('.peserta-new-total');
                if(result>0){
                    w.html('<div class=\'btn btn-xs btn-danger\'>'+result+'</div>');
                }else{
                    w.html('');
                }
            },
            error:function(xhr,status,error){
                errorMsg(error);
            }
        });
        
    }
    checkPesertaBaru();
    setInterval(function(){
        checkPesertaBaru();
    },".Yii::$app->params['timeout']['check_peserta_baru'].");
    ");
}
?>
<nav class="navbar-inverse my-navbar navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">PENDAFTARAN ONLINE</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <?php
                if(App::isInstansi()){
                    ?>
                    <li class="<?php echo Yii::$app->controller->id=="instansi-permintaan" ? "active" : "" ?>"><a href="<?php echo Url::to(['/instansi-permintaan']); ?>"><i class="fa fa-list"></i> Permintaan</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="instansi-profile" ? "active" : "" ?>"><a href="<?php echo Url::to(['/instansi-profile']); ?>"><i class="fa fa-user"></i> Profile</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="instansi-informasi" ? "active" : "" ?>"><a href="<?php echo Url::to(['/instansi-informasi/list']); ?>"><i class="fa fa-info-circle"></i> Informasi</a></li>
                    <?php
                }elseif(App::isDokter()){
                    ?>
                    <li class="<?php echo Yii::$app->controller->id=="admin" && ( Yii::$app->controller->action->id=="peserta-list" || Yii::$app->controller->action->id=="peserta-detail") ? "active" : "" ?>"><a href="<?php echo Url::to(['/admin/peserta-list']); ?>"><i class="fa fa-address-book"></i> Peserta <span class="peserta-new-total"></span></a></li>
                    <li class="<?php echo Yii::$app->controller->id=="instansi" ? "active" : "" ?>"><a href="<?php echo Url::to(['/instansi']); ?>"><i class="fa fa-user"></i> Instansi</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="instansi-permintaan" ? "active" : "" ?>"><a href="<?php echo Url::to(['/instansi-permintaan']); ?>"><i class="fa fa-list"></i> Permintaan Instansi</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="paket" ? "active" : "" ?>"><a href="<?php echo Url::to(['/paket']); ?>"><i class="fa fa-book"></i> Paket</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="jenis-mcu" ? "active" : "" ?>"><a href="<?php echo Url::to(['/jenis-mcu']); ?>"><i class="fa fa-list"></i> Jenis Pemeriksaan</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="informasi" ? "active" : "" ?>"><a href="<?php echo Url::to(['/informasi']); ?>"><i class="fa fa-info-circle"></i> Informasi</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="setting" ? "active" : "" ?>"><a href="<?php echo Url::to(['/setting']); ?>"><i class="fa fa-cog"></i> Setting</a></li>
                    <?php
                }elseif(App::isPesertaInstansi()){
                    ?>
                    <li class="<?php echo Yii::$app->controller->id=="site" && (in_array(Yii::$app->controller->action->id,['biodata','berkas','kuisioner-sosial','kuisioner-penyakit','kuisioner-anamnesa','selesai'])) ? "active" : "" ?>"><a href="<?php echo Url::to(['/site/biodata']); ?>"><i class="fa fa-pencil"></i> Pendaftaran</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="site" && Yii::$app->controller->action->id=="informasi" ? "active" : "" ?>"><a href="<?php echo Url::to(['/site/informasi']); ?>"><i class="fa fa-info-circle"></i> Informasi</a></li>
                    <?php
                }elseif(App::isPeserta() || App::isPasien()){
                    $user=Yii::$app->user->identity;
                    $shw=false;
                    if(isset($user->ud_approve_status)){
                        if($user->ud_approve_status=='2'){
                            $shw=true;
                        }
                    }
                    if($shw){
                        ?>
                        <li class="<?php echo Yii::$app->controller->id=="peserta-umum" && Yii::$app->controller->action->id=="daftar" ? "active" : "" ?>"><a href="<?php echo Url::to(['/peserta-umum/daftar']); ?>"><i class="fa fa-plus"></i> Daftar MCU</a></li>
                        <li class="<?php echo Yii::$app->controller->id=="peserta-umum" && Yii::$app->controller->action->id=="riwayat" ? "active" : "" ?>"><a href="<?php echo Url::to(['/peserta-umum/riwayat']); ?>"><i class="fa fa-list"></i> Riwayat</a></li>
                        <?php
                    }
                    ?>
                    <li class="<?php echo Yii::$app->controller->id=="peserta-umum" && Yii::$app->controller->action->id=="biodata" ? "active" : "" ?>"><a href="<?php echo Url::to(['/peserta-umum/biodata']); ?>"><i class="fa fa-user"></i> Biodata</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="peserta-umum" && Yii::$app->controller->action->id=="informasi" ? "active" : "" ?>"><a href="<?php echo Url::to(['/peserta-umum/informasi']); ?>"><i class="fa fa-info-circle"></i> Informasi</a></li>
                    <?php
                }elseif(App::isRm()){
                    ?>
                    <li class="<?php echo Yii::$app->controller->id=="admin" && ( Yii::$app->controller->action->id=="peserta-list" || Yii::$app->controller->action->id=="peserta-detail") ? "active" : "" ?>"><a href="<?php echo Url::to(['/admin/peserta-list']); ?>"><i class="fa fa-address-book"></i> Peserta MCU Umum <span class="peserta-new-total"></span></a></li>
                    <li class="<?php echo Yii::$app->controller->id=="pasien" ? "active" : "" ?>"><a href="<?php echo Url::to(['/pasien/index']); ?>"><i class="fa fa-user"></i> Pasien</a></li>
                    <li class="<?php echo Yii::$app->controller->id=="instansi-permintaan" ? "active" : "" ?>"><a href="<?php echo Url::to(['/instansi-permintaan']); ?>"><i class="fa fa-list"></i> Permintaan Instansi</a></li>
                    <?php
                }
                if(!Yii::$app->user->isGuest){
                    $nama=NULL;
                    if(App::isPeserta()){
                        $nama=Yii::$app->user->identity->ud_nama;
                    }elseif(App::isPasien()){
                        $nama=Yii::$app->user->identity->NAMA;
                    }elseif(App::isDokter() || App::isRm() || App::isInstansi()){
                        $nama=Yii::$app->user->identity->u_nama_depan;
                    }
                    ?><li><a href="#" class="btn-logout" title="logout"><i class="fa fa-power-off"></i> Logout <?php echo $nama!=NULL ? "[ ".$nama." ]" : ""; ?></a></li><?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>