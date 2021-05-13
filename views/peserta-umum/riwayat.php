<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
$this->title="Riwayat Pemeriksaan MCU";
?>
<div style="margin-bottom:10px; font-size:18px; text-align:center; border-bottom:1px solid #000;"><?php echo strtoupper($this->title); ?></div>
<?php Pjax::begin(['id'=>'pjax-riwayat']); ?>
<?= GridView::widget([
	'id'=>'grid-riwayat',
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
        [
            'label'=>'Tanggal Pemeriksaan',
            'attribute'=>'u_tgl_periksa',
            'value'=>function($d){
                return $d->u_tgl_periksa!=NULL ? date('d-m-Y',strtotime($d->u_tgl_periksa)) : NULL;
            },
            'filter'=>DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'u_tgl_periksa',
                'options'=>['class'=>'form-control','id'=>'u_tgl_periksa'],
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'autoclose'=>true,
                    'changeYear'=>true,
                    'changeMonth'=>true,
                ]
            ])
        ],
        [
            'label'=>'Jenis Pemeriksaan',
            'attribute'=>'u_jenis_mcu_id',
            'value'=>function($d){
                return $d->jenismcu!=NULL ? $d->jenismcu->jm_nama : NULL;
            },
            'filter'=>ArrayHelper::map($jenis_mcu,'jm_id','jm_nama')
        ],
		[
			'class' => 'yii\grid\ActionColumn',
			'header'=>'-',
			'template' => '{view}',
			'buttons'=>[
				'view'=>function($url,$model){
					return "<a href='".Url::to(['cetak-riwayat','id'=>$model['u_id']])."' class='btn btn-sm btn-primary' title='cetak bukti pendaftaran' data-pjax='0'  data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i></a>";
				},
			]
		],
	],
]); ?>
<?php Pjax::end(); ?>