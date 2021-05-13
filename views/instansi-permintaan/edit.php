<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
$this->title="Edit Biodata Peserta";
?>
<h4><?php echo $this->title; ?></h4>
<?php $form = ActiveForm::begin();  ?>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_nik')->textInput() ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_email')->textInput() ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_nama_depan')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'u_tgl_lahir')->widget(DatePicker::className(),[
            'dateFormat' => 'dd-MM-yyyy',
            'options'=>['class'=>'form-control','autocomplete'=>'off','readonly'=>true],
            'clientOptions'=>[
                'maxDate'=>date('d-m-Y'),
                'changeMonth'=>true,
                'changeYear'=>true,
            ]
        ]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'u_tmpt_lahir')->textInput() ?></div>
        <div class="col-md-3"><?= $form->field($model, 'u_jkel')->dropDownList(['L'=>'Laki-laki','P'=>'Perempuan']) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'u_no_hp')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'u_agama')->dropDownList(ArrayHelper::map($agama,'Kode','Agama')) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'u_status_nikah')->dropDownList(['K'=>'Kawin','T'=>'Belum Kawin','J'=>'Janda','D'=>'Duda']) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'u_kedudukan_keluarga')->dropDownList(['kepala keluarga'=>'Kepala Keluarga','anak'=>'Anak','istri'=>'Istri']) ?></div>
        <div class="col-md-3 wrap-istri" style="<?php echo $model->isNewRecord ? 'display:none;' : ( $model->u_kedudukan_keluarga!='istri' ? 'display:none;' : '' ) ?>"><?= $form->field($model, 'u_istri_ke')->dropDownList([1=>1,2,3,4]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_alamat')->textInput() ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_kab')->textInput() ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_provinsi')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'u_pendidikan')->dropDownList($pendidikan) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'u_pekerjaan')->dropDownList(ArrayHelper::map($pekerjaan,'Nomor','PerkerjaanJabatan')) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'u_jabatan_pekerjaan')->textInput() ?></div>
        <div class="col-md-3"><?= $form->field($model, 'u_alamat_pekerjaan')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_nama_ayah')->textInput() ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_nama_ibu')->textInput() ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_nama_pasangan')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($model, 'u_anggota_darurat')->dropDownList(['1'=>'Iya','0'=>'Tidak']) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'u_anggota_darurat_ket')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'u_tgl_terakhir_mcu')->widget(DatePicker::className(),[
                'dateFormat' => 'dd-MM-yyyy',
                'options'=>['class'=>'form-control','autocomplete'=>'off','readonly'=>'readonly'],
                'clientOptions'=>[
                    'maxDate'=>date('d-m-Y'),
                    'changeMonth'=>true,
                    'changeYear'=>true,
                ]
            ]) ?>
        </div>
        <div class="col-md-4"><?= $form->field($model, 'u_dokter')->textInput() ?></div>
        <div class="col-md-4"><?= $form->field($model, 'u_alamat_dokter')->textInput() ?></div>
    </div>
    <a href="<?php echo Url::to(['view','id'=>$up]) ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-save"></i> Simpan</button>
<?php ActiveForm::end(); ?>