<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title="Peserta Baru";
?>
<h4><?php echo $this->title; ?></h4>
<p><small>Pasien MCU pendaftaran online</small></p>
<?php Pjax::begin(['id'=>'pjax-peserta']); ?>
<?= GridView::widget([
    'id'=>'grid-peserta',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    // 'rowOptions'=>function($model, $key, $index, $grid){
    //     return ['class'=>$model->u_status=='0' ? "danger" : "",'title'=>$model->u_status=='0' ? "akun instansi tidak aktif" : ""];
    // },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'ud_nik',
        'ud_nama',
        [
            'label'=>'Tanggal Lahir',
            'attribute'=>'ud_tgl_lahir',
            'value'=>function($d){
                return $d->ud_tgl_lahir!=NULL ? date('d-m-Y',strtotime($d->ud_tgl_lahir)) : NULL;
            }
        ],
        [
            'label'=>'Status',
            'attribute'=>'ud_approve_status',
            'format'=>'raw',
            'value'=>function($data){
                if($data->ud_approve_status=='0'){
                    $h='<div class="btn btn-xs btn-danger">Tidak Disetujui</div>';
                }elseif($data->ud_approve_status=='1'){
                    $h= '<div class="btn btn-xs btn-warning">Direvisi</div>';
                }elseif($data->ud_approve_status=='2'){
                    $h= '<div class="btn btn-xs btn-success">Disetujui</div>';
                }else{
                    $h= '<div class="btn btn-xs btn-primary">Baru</div>';
                }
                return $h;
            },
            'filter'=>['0'=>'Tidak Disetujui','1'=>'Direvisi','2'=>'Disetujui','3'=>'Baru']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'-',
                'template' => '{view}',
                'buttons'=>[
                    'view'=>function($url,$model){
                        return "<a href='".$url."' data-pjax='0' class='btn btn-sm btn-info btn-edit' title='detail peserta' data-toggle='tooltip'><i class='fa fa-search'></i></a>"; 
                    }
                ]
        ],
    ],
]); ?>
<?php Pjax::end(); ?>