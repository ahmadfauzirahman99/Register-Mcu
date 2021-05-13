<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;
$this->title = 'Peserta';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $('.btn-search').click(function(e){
        e.preventDefault();
        $('.form-search').toggle('slow');
    });
    $('.btn-report').click(function(e){
        e.preventDefault();
        $.post('".Url::to(['report-form'])."',function(result){
            $('#mymodal').html(result).modal({show:true});
        });
    });
	$('.btn-rekap').click(function(e){
        e.preventDefault();
        $.post('".Url::to(['rekap-form'])."',function(result){
            $('#mymodal').html(result).modal({show:true});
        });
    });
");
?>
<div class="user-index">
    <h3><strong><?= Html::encode($this->title) ?></strong></h3>
    <p>
        <a href="#" class="btn btn-info btn-search"><i class="glyphicon glyphicon-search"></i> Pencarian</a>
        <a href="#" class="btn btn-primary btn-report"><i class="glyphicon glyphicon-file"></i> Laporan</a>
        <a href="#<?php //echo Url::to(['rekap-all']) ?>" class="btn btn-primary btn-rekap"><i class="fa fa-file"></i> Rekapitulasi</a>
    </p>
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
				'attribute'=>'u_finish_reg',
				'value'=>function($data){
					return $data->u_finish_reg=='1' ? 'Selesai Pendaftaran' : 'Belum Selesai Pendaftaran';
				},
				'filter'=>['0'=>'Belum Selesai Pendaftaran','1'=>'Selesai Pendaftaran']
			],
            [
                'attribute'=>'u_lulus_reg',
                'format'=>'raw',
                'value'=>function($data){
                    return $data->u_lulus_reg!=NULL ? ($data->u_lulus_reg=='0' ? '<div class="btn btn-sm btn-danger">Tidak Memenuhi Syarat</div>' : ($data->u_lulus_reg=='1' ? '<div class="btn btn-sm btn-success">Memenuhi Syarat</div>' : '<div class="btn btn-sm btn-warning">Dipertimbangkan</div>')) : '';
                },
                'filter'=>[
					'0'=>'Tidak Memenuhi Syarat',
					'1'=>'Memenuhi Syarat',
					'2'=>'Dipertimbangkan',
				]
            ],
			[
				'label'=>'Verifikator',
				'attribute'=>'u_verify_by',
				'value'=>function($data){
					return $data->u_verify_by!=NULL ? User::find()->where(['u_id'=>$data->u_verify_by])->asArray()->limit(1)->one()['u_nama'] : '';
				},
				'filter'=>ArrayHelper::map($verifikator,'u_id','u_nama')
			],
			'u_ket',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'-',
                    'template' => '{view}',
                    'buttons'=>[
                        'view'=>function($url,$model){
                            return "<a href='".$url."' target='_blank' data-pjax='0' class='btn btn-sm btn-info' title='detail peserta' data-toggle='tooltip'><i class='glyphicon glyphicon-search'></i></a>"; 
                        },
                        // 'update'=>function($url,$model){
                        //     return "<a href='".$url."' class='btn btn-primary btn-sm' title='edit peserta' data-toggle='tooltip'><i class='glyphicon glyphicon-pencil'></i></a>";
                        // },
                    ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<?php
/*if(Yii::$app->user->identity->u_level==3){
	echo Yii::$app->getSecurity()->generatePasswordHash('coronarsud44');
}*/