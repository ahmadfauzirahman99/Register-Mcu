<?php
use yii\helpers\Url;
$this->title="Selesai Pendaftaran";
if($user['u_finish_at']==NULL){
    $this->registerJs("
        $('.btn-selesai').click(function(){
            if(confirm('PASTIKAN DATA DAN BERKAS YANG ANDA UPLOAD SUDAH BENAR !!')){
                if(confirm('APAKAH ANDA SUDAH YAKIN MENGAKHIRI PENDAFTARAN !?')){
                    var btn=$(this);
                    var htm=btn.html();
                    setBtnLoading(btn,'Loading...');
                    $.ajax({
                        url:'".Url::to(['selesai-save'])."',
                        type:'post',
                        dataType:'json',
                        success:function(result){
                            if(result.status){
                                location.reload();
                            }
                            resetBtnLoading(btn,htm);
                        },
                        error:function(xhr,status,error){
                            errorMsg(error);
                            resetBtnLoading(btn,htm);
                        }
                    });
                }
            }
            return false;
        });
    ");
}
?>
<?php echo $this->render('timeline'); ?>
<div style="margin-bottom:10px; font-size:18px; text-align:center; border-bottom:1px solid #000;"><?php echo strtoupper($this->title); ?></div>
<div class="well">
    <?php
    if($user['u_finish_at']==NULL){
        ?>
        <div class="jumbotron">
            <h3><strong>PASTIKAN DATA SUDAH LENGKAP SEBELUM MENGAKHIRI PENDAFTARAN !</strong></h3>
            <h4><strong>DATA YANG SUDAH DIISI TIDAK BISA DIUBAH KEMBALI !</strong></h4>
            <h4><strong>Silahkan klik tombol dibawah untuk mengakhiri pendaftaran.</strong></h4>
            <p>
                <a href="#" class="btn btn-success btn-selesai"><i class="fa fa-flag-checkered"></i> Selesai Pendaftaran</a>
            </p>
        </div>
        <?php
    }else{
        ?>
        <div class="jumbotron">
            <h4><strong>SILAHKAN DOWNLOAD DAN CETAK BUKTI PENDAFTARAN</strong></h4>
            <p>
                <a href="<?php echo Url::to(['bukti-pendaftaran']); ?>" target="_blank" class="btn btn-success"><i class="fa fa-download"></i> Bukti Pendaftaran</a>
            </p>
        </div>
        <?php
    }
    ?>
</div>