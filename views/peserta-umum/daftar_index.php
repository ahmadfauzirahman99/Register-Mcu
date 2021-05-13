<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\web\View;
$this->title="Pendaftaran Pemeriksaan MCU";
$this->registerJs("
var enableDate=".json_encode($disable_now).";
",View::POS_HEAD);
$this->registerJs("
    $('#user-u_jenis_mcu_id').change(function(e){
        var id = $(this).val();
        var w=$('.wrap-keterangan');
        if(id){
            $.post('".Url::to(['jenis-mcu-keterangan'])."',{id:id},function(result){
                w.html(result);
            });
        }else{
            w.html('');
        }
    });
    $('#".$model->formName()."').on('beforeSubmit',function(e){
        e.preventDefault();
        var btn=$('.btn-submit');
        var htm=btn.html();
        setBtnLoading(btn,'Loading...');
        $.ajax({
            url:'".Url::to(['daftar-save'])."',
            type:'post',
            dataType:'json',
            data:$(this).serialize(),
            success:function(result){
                if(result.status){
                    successMsg(result.msg);
                    $('input[name=\'update\']').val(result.update);
                    $('.btn-next').show('slow');
                    $('.progress-daftar').removeClass('inactive progress-bar-warning').addClass('progress-bar-success active').find('.status-icon').html('<i class=\'fa fa-check-circle\'></i>');
                }else{
                    errorMsg(result.msg);
                }
                resetBtnLoading(btn,htm);
            },
            error:function(xhr,status,error){
                resetBtnLoading(btn,htm);
                errorMsg(error);
            }
        })
    }).on('submit',function(e){
        e.preventDefault();
    });
    $('input[name=\'User[is_riwayat_mcu]\']').change(function(e){
        var id=$(this).val();
        var w=$('.wrap-riwayat-mcu');
        if(id=='y'){
            w.show('slow');
            w.find('input[type=\'text\']').attr('required',true);
        }else{
            w.hide('slow');
            w.find('input[type=\'text\']').removeAttr('required').val('');
        }
    });
    $('#user-u_paket_id').change(function(e){
        var id =$(this).val();
        if(id){
            $.post('".Url::to(['paket-detail'])."',{id:id},function(result){
                $('.wrap-detail').html(result);
            });
        }else{
            $('.wrap-detail').html('');
        }
    });
");
?>
<?php echo $this->render('permintaan_timeline'); ?>
<div style="margin-bottom:10px; font-size:18px; text-align:center; border-bottom:1px solid #000;"><?php echo strtoupper($this->title); ?></div>
<p>Silahkan lengkapi form berikut : </p>
<?php $form = ActiveForm::begin(['id'=>$model->formName(),'action'=>'#']);  
    echo $form->field($model, 'u_nik',['template'=>'{input}','options'=>['tag'=>false],])->hiddenInput()->label(false);
    ?>
    <input type="hidden" name="update" value="<?php echo $model->u_id; ?>">
    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'u_tgl_periksa')->widget(DatePicker::className(),[
                'dateFormat' => 'dd-MM-yyyy',
                'options'=>['class'=>'form-control','autocomplete'=>'off','readonly'=>'readonly'],
                'clientOptions'=>[
                    'minDate'=>date('d-m-Y'),
                    'maxDate'=>date('d-m-Y',strtotime('tomorrow')),
                    'changeMonth'=>true,
                    'changeYear'=>true,
                    'beforeShowDay'=>new JsExpression('disabledDate')
                ]
            ])->hint('opsi pendaftaran mcu :<br>- pendaftaran untuk pemeriksaan bisa dilakukan H-1, atau<br>- pendaftaran hanya sampai jam '.$setting['batas_jam_daftar'].' pagi untuk pemeriksaan hari ini.') ?>
            <?= $form->field($model, 'u_jenis_mcu_id')->dropDownList(ArrayHelper::map($jenis_mcu,'jm_id','jm_nama'),['prompt'=>'']) ?>
            <?= $form->field($model, 'u_paket_id')->dropDownList(ArrayHelper::map($paket,'kode','nama'),['prompt'=>'']) ?>
            <?php echo $form->field($model, 'is_riwayat_mcu')->radioList(['y'=>'Pernah','n'=>'Belum Pernah'])->label('Riwayat Pemeriksaan MCU (jika ada) ?'); ?>
            <div class="wrap-riwayat-mcu" style="<?php echo $model->is_riwayat_mcu=='n' ? 'display:none;' : '' ?>">
                <?= $form->field($model, 'u_tgl_terakhir_mcu')->widget(DatePicker::className(),[
                    'dateFormat' => 'dd-MM-yyyy',
                    'options'=>['class'=>'form-control','autocomplete'=>'off','readonly'=>'readonly'],
                    'clientOptions'=>[
                        'maxDate'=>date('d-m-Y'),
                        'changeMonth'=>true,
                        'changeYear'=>true,
                        'beforeShowDay'=>new JsExpression('disabledDate')
                    ]
                ]) ?>
                <?= $form->field($model, 'u_dokter')->textInput() ?>
                <?= $form->field($model, 'u_alamat_dokter')->textInput() ?>
            </div>
            <?php
            if($model->u_finish_at==NULL){
                ?><button type="submit" class="btn btn-success btn-submit">Simpan <i class="fa fa-save"></i></button><?php
            }
            ?>
            <a href="<?php echo Url::to(['berkas']); ?>" class="btn btn-default btn-next" style="<?php echo $model->u_biodata_finish_at==NULL ? 'display:none;' : NULL ?>">Selanjutnya <i class="fa fa-angle-right"></i></a>
        </div>
        <div class="col-md-7">
            <div class="wrap-keterangan"><?php echo !$model->isNewRecord ? $this->render('jenis_mcu_ket',['jenis'=>$model->jenismcu]) : NULL ?></div>
            <div class="wrap-detail"><?php echo !$model->isNewRecord ? $this->render('daftar_paket_detail',['detail'=>$model->paket!=NULL ? ( $model->paket->tindakan ) : NULL ]) : NULL ?></div>
        </div>
    </div>
<?php ActiveForm::end(); ?>