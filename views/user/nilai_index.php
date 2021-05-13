<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;
use yii\bootstrap\ActiveForm;
$this->title = 'Nilai Peserta';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $('.btn-search').click(function(e){
        e.preventDefault();
        $('.form-search').toggle('slow');
    });
    $('.btn-report').click(function(e){
        e.preventDefault();
        $.post('".Url::to(['nilai-report-form'])."',function(result){
            $('#mymodal').html(result).modal({show:true});
        });
    });
    $('.btn-import').click(function(e){
        $('.form-import').toggle();
    });
    /*$('#import').on('change',function(){
        var el=$(this);
        var el_label=el.siblings('label');
        var label=el_label.html();

        el.attr('disabled',true);
        el_label.html('<i class=\'fa fa-refresh fa-spin\'></i> Uploading...');
        
        var file = el[0].files[0];
        if(file){
            var formData = new FormData();
            formData.append('berkas', file);
            $.ajax({
                url:'".Url::to(['nilai-import'])."',
                type:'post',
                dataType:'json',
                data:formData,
                contentType: false,
                cache: false,
                processData:false,
                success:function(result){
                    if(result.status){
                        toastr['success'](result.msg);
                    }else{
                        if(typeof result.msg =='object'){
                            $.each(result.msg,function(i,v){
                                toastr['error'](v);
                            });
                        }else{
                            toastr['error'](result.msg);
                        }
                    }
                    el.removeAttr('disabled').val('');
                    el_label.html(label);
                    console.log(result);
                },
                error:function(xhr,status,error){
                    el.removeAttr('disabled').val('');
                    el_label.html(label);
                    toastr['error'](error);
                }
            });
        }else{
            toastr['error']('Terjadi kesalahan dalam memilih file, silahkan coba kembali');
        }
    });*/
");
?>
<div class="user-index">
    <h3><strong><?= Html::encode($this->title) ?></strong></h3>
    <p>
        <a href="#" class="btn btn-info btn-search"><i class="glyphicon glyphicon-search"></i> Pencarian</a>
        <a href="#" class="btn btn-primary btn-report"><i class="glyphicon glyphicon-file"></i> Laporan</a>
        <a href="#" class="btn btn-primary btn-import"><i class="glyphicon glyphicon-file"></i> Import</a>
    </p>
    <div class="form-import" style="display:none;">
        <?php $form = ActiveForm::begin(['action'=>Url::to(['nilai-import']),'options'=>['target'=>'_blank','enctype'=>'multipart/form-data']]); ?>
            <div class="form-group">
                <label for="berkas">Import Excel</label>
                <input type="file" name="berkas" id="import">
            </div>
            <button type="submit">SUBMIT</button>
        <?php ActiveForm::end(); ?>
    </div>
    <?php Pjax::begin(); ?>
    <div class="form-search" style="display:none;"><?php echo $this->render('_search', ['model' => $searchModel,'pendidikan'=>$pendidikan,'formasi'=>$formasi,]); ?></div>
    <?= GridView::widget([
		'id'=>'grid-peserta',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model, $key, $index, $grid){
            return ['class'=>$model->u_finish_reg=='0' ? "warning" : "",'title'=>$model->u_finish_reg=='0' ? "pendaftaran belum selesai" : ""];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'u_nik',
            'u_nama',
            [
                'attribute'=>'u_formasi_id',
                'value'=>function($data){
                    return $data->formasi!=NULL ? $data->formasipendidikan->jp_nama.' - '.$data->formasi->f_nama_formasi.' ('.$data->formasi->f_pendidikan.')' : '';
                },
                'filter'=>ArrayHelper::map($formasi,'id','nama')
            ],
			[
                'label'=>'Menyetujui Ketentuan Ujian',
                'attribute'=>'u_setuju_ujian',
                'value'=>function($data){
                    return $data->u_setuju_ujian!=NULL ? ($data->u_setuju_ujian==1 ? 'Menyetujui Ketentuan' : 'Belum Menyetujui Ketentuan') : '';
                },
                'filter'=>['1'=>'Menyetujui','2'=>'Tidak Menyetujui']
            ],
            'u_nilai_ujian_twk',
            'u_nilai_ujian_tiu',
            'u_nilai_ujian_tkp',
            'u_nilai_ujian_total',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'-',
                    'template' => '{view}',
                    'buttons'=>[
                        'view'=>function($url,$model){
                            return "<a href='".$url."' target='_blank' data-pjax='0' class='btn btn-sm btn-info' title='detail peserta' data-toggle='tooltip'><i class='glyphicon glyphicon-search'></i></a>"; 
                        },
                    ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>