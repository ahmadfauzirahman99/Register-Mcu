<?php

use app\components\DynamicFormWidget;
use app\widgets\App;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Informasi Peserta';
$this->registerJs("
$('#user-u_kedudukan_keluarga').change(function(e){
    e.preventDefault();
    var el_istri=$('.wrap-istri');
    var el_pasangan=$('#user-u_nama_pasangan');
    var id=$(this).val();
    if(id=='istri'){
        el_istri.show('slow');
        el_pasangan.removeAttr('disabled');
    }else if(id=='anak'){
        el_pasangan.val('').attr('disabled',true);
    }else{
        el_pasangan.removeAttr('disabled');
        el_istri.hide('slow');
    }
});

$('#user-u_anggota_darurat').change(function(e){
    var id=$(this).val();
    var f=$('#user-u_anggota_darurat_ket');
    if(id==1){
        f.removeAttr('disabled').attr('required',true);
    }else{
        f.val('');
        f.removeAttr('required').attr('disabled',true);
    }
});
$('#" . $model->formName() . "').on('beforeSubmit',function(e){
    e.preventDefault();
    var btn=$('.btn-submit');
    var htm=btn.html();
    setBtnLoading(btn,'Menyimpan...');
    $.ajax({
        url:'" . Url::to(['biodata-save']) . "',
        type:'post',
        dataType:'json',
        data:$(this).serialize(),
        success:function(result){
            if(result.status){
                successMsg(result.msg);
                $('.btn-next').show('slow');
                $('.progress-daftar').removeClass('inactive progress-bar-warning').addClass('progress-bar-success active').find('.status-icon').html('<i class=\'fa fa-check-circle\'></i>');
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
");
$this->registerCss("
input[type='text']{
    text-transform: uppercase;
}
");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php echo $this->render('timeline'); ?>
            <h3 class="page-header text-center">.: Biodata :.</h3>
            <p>Silahkan lengkapi form biodata berikut :</p>
            <?php $form = ActiveForm::begin(['id' => $model->formName(), 'action' => '#']); ?>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'u_nik')->textInput(['disabled' => true]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_email')->textInput(['disabled' => $isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_nama_depan')->textInput(['disabled' => true]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($model, 'u_tgl_lahir')->widget(DatePicker::className(), [
                                            'dateFormat' => 'dd-MM-yyyy',
                                            'options' => ['class' => 'form-control', 'autocomplete' => 'off', 'disabled' => true],
                                            'clientOptions' => [
                                                'maxDate' => date('d-m-Y'),
                                                'changeMonth' => true,
                                                'changeYear' => true,
                                            ]
                                        ]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_tmpt_lahir')->textInput(['disabled' => $isRegClose]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_jkel')->dropDownList(['L' => 'Laki-laki', 'P' => 'Perempuan']) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_no_hp')->textInput(['disabled' => $isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($model, 'u_agama')->dropDownList(ArrayHelper::map($agama, 'Kode', 'Agama'), ['disabled' => $isRegClose]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_status_nikah')->dropDownList(['K' => 'Kawin', 'T' => 'Belum Kawin', 'J' => 'Janda', 'D' => 'Duda', 'Bercerai' => 'Bercerai'], ['disabled' => $isRegClose]) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'u_kedudukan_keluarga')->dropDownList(['kepala keluarga' => 'Kepala Keluarga', 'anak' => 'Anak', 'istri' => 'Istri'], ['disabled' => $isRegClose]) ?></div>
                <div class="col-md-3 wrap-istri" style="<?php echo $model->isNewRecord ? 'display:none;' : ($model->u_kedudukan_keluarga != 'istri' ? 'display:none;' : '') ?>"><?= $form->field($model, 'u_istri_ke')->dropDownList([1 => 1, 2, 3, 4], ['disabled' => $isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'u_alamat')->textInput(['disabled' => $isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_kab')->textInput(['disabled' => $isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_provinsi')->textInput(['disabled' => $isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'u_pendidikan')->dropDownList($pendidikan, ['disabled' => $isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_pekerjaan')->dropDownList(ArrayHelper::map($pekerjaan, 'Nomor', 'PerkerjaanJabatan'), ['disabled' => true]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_jabatan_pekerjaan')->textInput(['disabled' => $isRegClose]) ?></div>
            </div>
            <div class="row">

                <div class="col-md-4"><?= $form->field($model, 'u_nama_pasangan')->textInput(['disabled' => $isRegClose]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'u_anggota_darurat')->dropDownList(['1' => 'Iya', '0' => 'Tidak'], ['disabled' => $isRegClose]) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'u_anggota_darurat_ket')->textInput(['disabled' => $isRegClose ? $isRegClose : ($model->u_anggota_darurat != 1 ? true : false)]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'u_tgl_terakhir_mcu')->widget(DatePicker::className(), [
                                            'dateFormat' => 'dd-MM-yyyy',
                                            'options' => ['class' => 'form-control', 'autocomplete' => 'off', 'disabled' => $isRegClose, 'readonly' => $isRegClose ? false : 'readonly'],
                                            'clientOptions' => [
                                                'maxDate' => date('d-m-Y'),
                                                'changeMonth' => true,
                                                'changeYear' => true,
                                            ]
                                        ]) ?>
                </div>
                <div class="col-md-4"><?= $form->field($model, 'u_dokter')->textInput(['disabled' => $isRegClose]) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'u_alamat_dokter')->textInput(['disabled' => $isRegClose]) ?></div>
            </div>

            <div class="row">
                <?= $form->field($modelUserDetail, 'no_rm')->hiddenInput(['value' => $model->u_nik])->label(false) ?>
                <?php if ($model->u_jkel == 'P') { ?>
                    <h3 class="page-header text-center">.: Data Orang Tau :.</h3>
                    <p>Silahkan lengkapi form biodata berikut :</p>

                    <div class="col-lg-6">
                        <?= $form->field($modelUserDetail, 'apakah_anda_anak_pertama')->dropDownList(['Ke 1' => 'Ke 1', 'Ke 2' => 'Ke 2',], ['prompt' => 'Apakah Anda Anak Ke 1?'])->label('Anak Ke Berapa Anda ?') ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($modelUserDetail, 'tanggal_pernikahan')->widget(DatePicker::className(), [
                            'dateFormat' => 'yyyy-MM-dd',
                            'options' => ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Masukkan Tanggal Pernikahan'],
                            'clientOptions' => [
                                // 'maxDate' => date('d-m-Y'),
                                // 'changeMonth' => true,
                                // 'changeYear' => true,
                            ]
                        ])->label('Tanggal Pernikahan Orang Tua') ?>
                    </div>
                    <div class="col-md-4"><?= $form->field($model, 'u_nama_ayah')->textInput(['disabled' => $isRegClose]) ?></div>
                    <div class="col-md-4"><?= $form->field($model, 'u_nama_ibu')->textInput(['disabled' => $isRegClose]) ?></div>
                <?php } ?>
            </div>

            <hr>

            <h3 class="page-header text-center">.: Nama Lengkap Saudara Kandung :.</h3>

            <div class="row">
                <div class="col-md-12">

                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper',
                        'widgetBody' => '.form-options-body',
                        'widgetItem' => '.form-options-item',
                        'min' => 1,
                        'insertButton' => '.add-item',
                        'deleteButton' => '.delete-item',
                        'model' => $modelDetail[0],
                        'formId' => $model->formName(),
                        'formFields' => [
                            'id_user_borther',
                            'nik',
                            'nama_lengkap_saudara_sekandung',
                            'hubungan_persaudaran',
                            'jenis_kelamin',
                            'nik_saudara'
                        ],
                    ]); ?>
                    <table class="table-list-item table table-bordered table-hover" style="width: 100%;">
                        <thead class="bg-teal" style="text-align: center;">
                            <th style="width: 0.5%">#</th>
                            <th style="width: 30%">Nama Lengkap Saudara Sekandung</th>
                            <th style="width: 30%">Nik Saudara Sekandung</th>

                            <th style="width: 20%">Hubungan Persaudaran</th>
                            <th style="width: 30%">Jenis Kelamin</th>
                            <th></th>

                        </thead>

                        <tbody class="form-options-body">
                            <?php foreach ($modelDetail as $i => $modelDetail) : ?>
                                <tr class="form-options-item">
                                    <?php
                                    // necessary for update action.
                                    if (!$modelDetail->isNewRecord) {
                                        echo Html::activeHiddenInput($modelDetail, "[{$i}]id_user_brother");
                                    }
                                    ?>
                                    <td style="text-align: center">
                                        <span class="nomor">
                                            <?= ($i + 1) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= $form->field($modelDetail, "[{$i}]nama_lengkap_saudara_sekandung")->textInput(['maxlength' => true, 'placeholder' => 'Nama Lengkap Saudara Sekandung'])->label(false) ?>
                                    </td>
                                    <td>
                                        <?= $form->field($modelDetail, "[{$i}]nik_saudara")->textInput(['maxlength' => true, 'placeholder' => 'NIK Saudara Sekandung'])->label(false) ?>
                                    </td>
                                    <td>
                                        <?= $form->field($modelDetail, "[{$i}]hubungan_persaudaran")->dropDownList([
                                            'Ayah dan Ibu Sama' => 'Ayah dan Ibu Sama',
                                            '1 Ayah' => '1 Ayah',
                                            '1 Ibu' => '1 Ibu',
                                        ], ['prompt' => 'Pilih Hubungan Persaudaran'])->label(false) ?>
                                    </td>
                                    <td>
                                        <?= $form->field($modelDetail, "[{$i}]jenis_kelamin")->dropDownList([
                                            'L' => 'Laki-Laki',
                                            'P' => 'Perempuan',
                                        ], ['prompt' => 'Pilih Jenis Kelamin'])->label(false) ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="delete-item btn btn-md" title="Hapus Item">
                                            <i class="glyphicon glyphicon-trash text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td style="text-align: center;" colspan="4"></td>
                                <td>
                                    <button type="button" class="btn-block add-item btn btn-md" title="Tambah Item">
                                        <i class="glyphicon glyphicon-plus text-info"></i>
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <?php DynamicFormWidget::end(); ?>
                </div>
            </div>
            <hr>
            <h3 class="page-header text-center">.: Nama Pasangan :.</h3>
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper2',
                'widgetBody' => '.form-options-body-pasangan',
                'widgetItem' => '.form-options-item-pasangan',
                'min' => 1,
                'insertButton' => '.add-item-pasangan',
                'deleteButton' => '.delete-item-pasangan',
                'model' => $namaPasanganDetail[0],
                'formId' => $model->formName(),
                'formFields' => [
                    'id_nama_pasangan',
                    'nama_pasangan',
                    'nik',
                    'jenis_kelamin',
                    'tanggal_pernikahan',
                ],
            ]); ?>
            <table class="table-list-item table table-bordered table-hover" style="width: 100%;">
                <thead class="bg-teal" style="text-align: center;">
                    <th style="width: 0.5%">#</th>
                    <th style="width: 30%">Nama</th>
                    <th style="width: 30%">Nik</th>

                    <th style="width: 20%">Jenis Kelamin</th>
                    <th style="width: 30%">Tanggal Pernikahan</th>
                    <th></th>

                </thead>


                <tbody class="form-options-body-pasangan">
                    <?php foreach ($namaPasanganDetail as $z => $namaPasanganDetail) { ?>
                        <tr class="form-options-item-pasangan">
                            <?php
                            // necessary for update action.
                            if (!$namaPasanganDetail->isNewRecord) {
                                echo Html::activeHiddenInput($namaPasanganDetail, "[{$z}]id_nama_pasangan");
                            }
                            ?>
                            <td style="text-align: center">
                                <span class="nomor">
                                    <?= ($z + 1) ?>
                                </span>
                            </td>
                            <td>
                                <?= $form->field($namaPasanganDetail, "[{$z}]nama_pasangan")->textInput(['maxlength' => true, 'placeholder' => 'Nama Pasangan Anda'])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($namaPasanganDetail, "[{$z}]nik")->textInput(['maxlength' => true, 'placeholder' => 'Nik Pasangan Anda'])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($namaPasanganDetail, "[{$z}]jenis_kelamin")->dropDownList([
                                    'L' => 'Laki-Laki',
                                    'P' => 'Perempuan',
                                ], ['prompt' => 'Pilih Jenis Kelamin'])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($namaPasanganDetail, "[{$z}]tanggal_pernikahan")->widget(DatePicker::className(), [
                                    'dateFormat' => 'yyyy-MM-dd',
                                    'options' => ['class' => 'form-control', 'autocomplete' => 'off'],

                                ])->label(false) ?>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="delete-item-pasangan btn btn-md" title="Hapus Item">
                                    <i class="glyphicon glyphicon-trash text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td style="text-align: center;" colspan="4"></td>
                        <td>
                            <button type="button" class="btn-block add-item-pasangan btn btn-md" title="Tambah Item">
                                <i class="glyphicon glyphicon-plus text-info"></i>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php DynamicFormWidget::end(); ?>
            <hr>
            <h3 class="page-header text-center">.: Nama Anak :.</h3>
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper3',
                'widgetBody' => '.form-options-body-anak',
                'widgetItem' => '.form-options-item-anak',
                'min' => 1,
                'insertButton' => '.add-item-anak',
                'deleteButton' => '.delete-item-anak',
                'model' => $modelNamaAnak[0],
                'formId' => $model->formName(),
                'formFields' => [
                    'id_anak',
                    'nama_anak',
                    'jenis_kelamin',
                    'nik_anak',
                ],
            ]); ?>
            <table class="table-list-item table table-bordered table-hover" style="width: 100%;">
                <thead class="bg-teal" style="text-align: center;">
                    <th style="width: 0.5%">#</th>
                    <th style="width: 30%">Nama Anak</th>
                    <th style="width: 30%">Nik Anak</th>

                    <th style="width: 20%">Jenis Kelamin</th>
                    <th></th>

                </thead>


                <tbody class="form-options-body-anak">
                    <?php foreach ($modelNamaAnak as $k => $modelNamaAnak) { ?>
                        <tr class="form-options-item-anak">
                            <?php
                            // necessary for update action.
                            if (!$modelNamaAnak->isNewRecord) {
                                echo Html::activeHiddenInput($modelNamaAnak, "[{$k}]id_anak");
                            }
                            ?>
                            <td style="text-align: center">
                                <span class="nomor">
                                    <?= ($k + 1) ?>
                                </span>
                            </td>
                            <td>
                                <?= $form->field($modelNamaAnak, "[{$k}]nama_anak")->textInput(['maxlength' => true, 'placeholder' => 'Nama Anak Anda'])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($modelNamaAnak, "[{$k}]nik_anak")->textInput(['maxlength' => true, 'placeholder' => 'Nik Anak Anda'])->label(false) ?>
                            </td>
                            <td>

                                <?= $form->field($modelNamaAnak, "[{$k}]jenis_kelamin")->dropDownList([
                                    'L' => 'Laki-Laki',
                                    'P' => 'Perempuan',
                                ], ['prompt' => 'Pilih Jenis Kelamin'])->label(false) ?>
                            </td>

                            <td style="text-align: center;">
                                <button type="button" class="delete-item-pasangan btn btn-md" title="Hapus Item">
                                    <i class="glyphicon glyphicon-trash text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td style="text-align: center;" colspan="4"></td>
                        <td>
                            <button type="button" class="btn-block add-item-anak btn btn-md" title="Tambah Item">
                                <i class="glyphicon glyphicon-plus text-info"></i>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php DynamicFormWidget::end(); ?>

            <?php
            if (!$isRegClose) {
            ?>
                <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-save"></i> Simpan</button>

            <?php
            }
            ?><span class="btn-next" style="<?php echo $model->u_biodata_finish_at == NULL ? "display:none;" : "" ?>"><a href="<?php echo Url::to(['berkas']); ?>" class="btn btn-default"><i class="fa fa-arrow-right"></i> Selanjutnya (Upload Berkas)</a></span>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<?php
$this->registerJs($this->render('biodata.js'), View::POS_END);
?>