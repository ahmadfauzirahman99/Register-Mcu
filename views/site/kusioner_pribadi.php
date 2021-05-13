<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\widgets\App;
$this->registerJs("
$('input[name=\'UserKusionerBiodata[is_sebelum]\']').click(function(e){
    var id=$(this).val();
    var wrap = $('.sebelum-profesi');
    var detail=$('.wrap-sebelum-detail');
    if(id=='y'){
        wrap.show('slow');
        detail.show('slow');
        wrap.find('input').attr('required',true);
        detail.find('textarea').attr('required',true);
    }else{
        wrap.hide('slow');
        detail.hide('slow');
        wrap.find('input,textarea').removeAttr('required');
        detail.find('textarea').removeAttr('required');
    }
});
$('input[name=\'UserKusionerBiodata[is_sekarang]\']').click(function(e){
    var id=$(this).val();
    var wrap = $('.skrg-profesi');
    var detail = $('.wrap-skrg-detail');
    if(id=='y'){
        wrap.show('slow');
        detail.show('slow');
        wrap.find('input').attr('required',true);
        detail.find('textarea').attr('required',true);
    }else{
        wrap.hide('slow');
        detail.hide('slow');
        wrap.find('input').removeAttr('required');
        detail.find('textarea').removeAttr('required');
    }
});
$('input[name=\'UserKusionerBiodata[is_dituju]\']').click(function(e){
    var id=$(this).val();
    var wrap = $('.dituju-profesi');
    var detail = $('.wrap-dituju-detail');
    if(id=='y'){
        wrap.show('slow');
        detail.show('slow');
        wrap.find('input').attr('required',true);
        detail.find('textarea').attr('required',true);
    }else{
        wrap.hide('slow');
        detail.hide('slow');
        wrap.find('input').removeAttr('required');
        detail.find('textarea').removeAttr('required');
    }
});
$('.table-riwayat-pekerjaan tbody tr td').on('click','input[type=\'radio\']',function(){
    var el = $(this);
    var val=el.val();
    var wrap_jls=el.parent().parent().siblings('.penjelasan').find('input[type=\'text\']');
    if(val==1){
        wrap_jls.attr('required',true).attr('disabled',false).focus();
    }else{
        wrap_jls.val('').removeAttr('required').attr('disabled',true);
    }
});
$('#".$model->formName()."').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'".Url::to(['kuisioner-sosial-save'])."',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                $('input[name=\'update\']').val(result.id);
                successMsg(result.msg);
                $('.btn-next').show('slow');
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
$('.btn-next').click(function(e){
    $('#tab-riwayat a[href=\'#profile\']').tab('show');
});
");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']); ?>
                <input type="hidden" name="update" value="<?php echo $model->ukb_id; ?>">
                <?php echo $form->field($model,'ukb_user_id',['template'=>'{input}','options' => ['tag' => false]])->hiddenInput()->label(false);  ?>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <label>Apakah pernah memiliki pekerjaan sebelumnya ?</label><br>
                        <?php echo $form->field($model, 'is_sebelum')->radioList(['y'=>'Ada','n'=>'Tidak Ada'])->label(false); ?>
                    </div>
                    <div class="col-md-4">
                        <label>Apakah sekarang sudah memiliki pekerjaan ?</label><br>
                        <?php echo $form->field($model, 'is_sekarang')->radioList(['y'=>'Ada','n'=>'Tidak Ada'])->label(false); ?>
                    </div>
                    <div class="col-md-4">
                        <label>Apakah memiliki pekerjaan yang akan dituju/dilamar ?</label><br>
                        <?php echo $form->field($model, 'is_dituju')->radioList(['y'=>'Ada','n'=>'Tidak Ada'])->label(false); ?>
                    </div>
                </div>
                <hr>
                <div style="font-size:17px; font-weight:bolder; border-bottom:1px solid #000; margin-top:10px; margin-bottom:15px;">PEKERJAAN/PROFESI</div>
                <div class="row sebelum-profesi" style="<?php echo $model->isNewRecord ? "display:none;" : ( $model->is_sebelum=="n" ? "display:none;" : "" ) ?>">
                    <div class="col-md-6"><?php echo $form->field($model, 'ukb_krj_sebelum')->textInput() ?></div>
                    <div class="col-md-6"><?= $form->field($model, 'ukb_krj_sebelum_perusahaan')->textInput() ?></div>
                </div>
                <div class="row skrg-profesi" style="<?php echo $model->isNewRecord ? "display:none;" : ( $model->is_sekarang=="n" ? "display:none;" : "" ) ?>">
                    <div class="col-md-6"><?= $form->field($model, 'ukb_krj_skrg')->textInput() ?></div>
                    <div class="col-md-6"><?= $form->field($model, 'ukb_krj_skrg_perusahaan')->textInput() ?></div>
                </div>
                <div class="row dituju-profesi" style="<?php echo $model->isNewRecord ? "display:none;" : ( $model->is_dituju=="n" ? "display:none;" : "" ) ?>">
                    <div class="col-md-6"><?= $form->field($model, 'ukb_krj_dituju')->textInput() ?></div>
                    <div class="col-md-6"><?= $form->field($model, 'ukb_krj_dituju_perusahaan')->textInput() ?></div>
                </div>
                <div class="wrap-sebelum-detail" style="<?php echo $model->isNewRecord ? "display:none;" : ( $model->is_sebelum=='n' ? "display:none;" : "" ) ?>">
                    <div style="font-size:17px; font-weight:bolder; border-bottom:1px solid #000; margin-top:10px; margin-bottom:15px;">RIWAYAT PEKERJAAN SEBELUMNYA</div>
                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>Uraian</th>
                                <th>Pekerjaan Utama</th>
                                <th>Pekerjaan Tambahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Uraian Tugas<br><i>Uraian fungsi dan tanggungjawab dalam suatu pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_sblm_utama_uraian')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_sblm_tambah_uraian')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                            <tr>
                                <td>Target Kerja<br><i>Sasaran yang telah ditetapkan untuk dicapai dalam suatu pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_sblm_utama_target')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_sblm_tambah_target')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                            <tr>
                                <td>Cara Kerja<br><i>Tahapan yang dilakukan sehingga tercapai tujuan pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_sblm_utama_cara')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_sblm_tambah_cara')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                            <tr>
                                <td>Alat Kerja<br><i>Benda yang digunakan untuk mengerjakan sesuatu untuk mempermudah pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_sblm_utama_alat')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_sblm_tambah_alat')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="wrap-skrg-detail" style="<?php echo $model->isNewRecord ? "display:none;" : ( $model->is_sekarang=='n' ? "display:none;" : "" ) ?>">
                    <div style="font-size:17px; font-weight:bolder; border-bottom:1px solid #000; margin-top:10px; margin-bottom:15px;">PEKERJAAN SEKARANG</div>
                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>Uraian</th>
                                <th>Pekerjaan Utama</th>
                                <th>Pekerjaan Tambahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Uraian Tugas<br><i>Uraian fungsi dan tanggungjawab dalam suatu pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_skrg_utama_uraian')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_skrg_tambah_uraian')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                            <tr>
                                <td>Target Kerja<br><i>Sasaran yang telah ditetapkan untuk dicapai dalam suatu pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_skrg_utama_target')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_skrg_tambah_target')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                            <tr>
                                <td>Cara Kerja<br><i>Tahapan yang dilakukan sehingga tercapai tujuan pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_skrg_utama_cara')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_skrg_tambah_cara')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                            <tr>
                                <td>Alat Kerja<br><i>Benda yang digunakan untuk mengerjakan sesuatu untuk mempermudah pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_skrg_utama_alat')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_skrg_tambah_alat')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="wrap-dituju-detail" style="<?php echo $model->isNewRecord ? "display:none;" : ( $model->is_sekarang=='n' ? "display:none;" : "" ) ?>">
                    <div style="font-size:17px; font-weight:bolder; border-bottom:1px solid #000; margin-top:10px; margin-bottom:15px;">PEKERJAAN YANG DITUJU/DILAMAR</div>
                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>Uraian</th>
                                <th>Pekerjaan Utama</th>
                                <th>Pekerjaan Tambahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Uraian Tugas<br><i>Uraian fungsi dan tanggungjawab dalam suatu pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_dituju_utama_uraian')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_dituju_tambah_uraian')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                            <tr>
                                <td>Target Kerja<br><i>Sasaran yang telah ditetapkan untuk dicapai dalam suatu pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_dituju_utama_target')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_dituju_tambah_target')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                            <tr>
                                <td>Cara Kerja<br><i>Tahapan yang dilakukan sehingga tercapai tujuan pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_dituju_utama_cara')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_dituju_tambah_cara')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                            <tr>
                                <td>Alat Kerja<br><i>Benda yang digunakan untuk mengerjakan sesuatu untuk mempermudah pekerjaan</i></td>
                                <td><?php echo $form->field($model, 'ukb_dituju_utama_alat')->textArea(['rows'=>2])->label(false) ?></td>
                                <td><?php echo $form->field($model, 'ukb_dituju_tambah_alat')->textArea(['rows'=>2])->label(false) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <table class="table table-bordered table-riwayat-pekerjaan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th></th>
                            <th width="8%">Pilihan</th>
                            <th width='40%'>Jelaskan (Jika iya)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo App::riwayatSosial($kuisioner_sosial); ?>
                    </tbody>
                </table>
                <a href="<?php echo Url::to(['berkas']); ?>" class="btn btn-default btn-md" title="klik untuk menuju form upload berkas"><i class="fa fa-arrow-left"></i> Sebelumnya (Upload Berkas)</a>
                <?php if(!$isRegClose): ?>
                    <button type="submit" class="btn btn-success btn-md btn-submit"><i class="fa fa-save"></i> Simpan Riwayat Sosial</button>
                <?php endif; ?>
                <a href="#profile" class="btn btn-default btn-md btn-next" style="<?php echo $model->isNewRecord ? "display:none;" : ( Yii::$app->user->identity->u_kuisioner1_finish_at==NULL ? "display:none;" : "" ) ?>"><i class="fa fa-arrow-right"></i> Selanjutnya (Riwayat Penyakit)</a>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>