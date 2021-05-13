<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\widgets\AppUmum;
use app\models\Kuisioner;
$this->title="Kuisioner Anamnesa";
$this->registerJs("
$('.table-riwayat-anamnesa tbody tr td').on('click','input[type=\'radio\']',function(){
    var el = $(this);
    var val=el.val();
    var wrap_jls=el.parent().parent().siblings('.penjelasan').find('input[type=\'text\']');
    if(val==1){
        wrap_jls.attr('required',true).attr('disabled',false).focus();
    }else{
        wrap_jls.val('').removeAttr('required').attr('disabled',true);
    }
});

$('#form-riwayat-anamnesa').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'".Url::to(['kuisioner-preemployee-save'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                $('input[name=\'update\']').val(result.id);
                successMsg(result.msg);
                $('.btn-next').show('slow');
                $('.progress-kuisioner3').removeClass('inactive progress-bar-warning').addClass('active progress-bar-success').find('.status-icon').html('<i class=\'fa fa-check-circle\'></i>');
            }else{
                $.each(result.msg,function(i,v){
                    errorMsg(v);
                });
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
$('.btn-before').click(function(e){
    $('#tab-riwayat a[href=\'#profile\']').tab('show');
});
");
?>
<?php echo $this->render('permintaan_timeline'); ?>
<div style="margin-bottom:10px; font-size:18px; text-align:center; border-bottom:1px solid #000;"><?php echo strtoupper($this->title); ?></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(['id'=>'form-riwayat-anamnesa','action'=>'#']); ?>
                <?php
                foreach($kategori_kuisioner_cpns as $kk){
                    $data=Kuisioner::find()->where(['kk_id'=>$kk['kk_id']])->asArray()->all();
                    ?>
                    <!-- <div style="font-size:17px; font-weight:bolder; border-bottom:1px solid #000; margin-top:10px; margin-bottom:15px;"><?php echo $kk['kk_nama_indo']; ?></div> -->
                    <strong><?php echo $kk['kk_ket_ind']; ?></strong><br>
                    <small><i><?php echo strtolower($kk['kk_ket_eng']); ?></i></small>
                    <table class="table table-bordered table-riwayat-anamnesa">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th></th>
                                <th width="8%">Pilihan</th>
                                <th width='40%'>Jelaskan (Jika iya)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo AppUmum::riwayatPenyakit($data,5,false,$daftar); ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
                <a href="#home" class="btn btn-default btn-md btn-before"><i class="fa fa-arrow-left"></i> Sebelumnya (Riwayat Penyakit)</a>
                <?php if(!$is_reg_close): ?>
                    <button type="submit" class="btn btn-success btn-md btn-submit"><i class="fa fa-save"></i> Simpan Kuisioner</button>
                <?php endif; ?>
                <a href="<?php echo Url::to(['selesai']); ?>" class="btn btn-default btn-md btn-next" style="<?php echo $daftar['u_kuisioner3_finish_at']==NULL ? "display:none;" : "" ?>"><i class="fa fa-arrow-right"></i> Selanjutnya (<?php echo !$is_reg_close ? "Selesai" : "Bukti Pendaftaran" ?>)</a>
                
                <!-- <a href="<?php echo Url::to(['kuisioner-penyakit']) ?>" class="btn btn-default btn-md btn-next" style="<?php echo $daftar['u_kuisioner1_finish_at']==NULL ? "display:none;" : "" ?>"><i class="fa fa-arrow-right"></i> Selanjutnya (Kuisioner Riwayat Penyakit)</a> -->
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>