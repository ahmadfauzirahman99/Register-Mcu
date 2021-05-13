<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;
$this->title = 'Instansi';
$this->registerJs("
function editBtn(){
    $('#grid-instansi .table tbody tr td').on('click','.btn-edit',function(e){
        e.preventDefault();
        var id=$(this).attr('data-id');
        var btn=$(this);
        var htm=btn.html();
        formModal({url:'".Url::to(['form'])."',data:{id:id},loading:{btn:btn,html:htm}});
    });
}
editBtn();
$(document).on('ready pjax:success',function(){
    editBtn();
});
");
?>
<div class="user-index">
    <h3><strong><?= $this->title ?></strong></h3>
    <?php Pjax::begin(['id'=>'pjax-instansi']); ?>
    <?= GridView::widget([
		'id'=>'grid-instansi',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model, $key, $index, $grid){
            return ['class'=>$model->u_status=='0' ? "danger" : "",'title'=>$model->u_status=='0' ? "akun instansi tidak aktif" : ""];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'Nama Instansi',
                'attribute'=>'u_nama_depan',
            ],
            'u_nama_petugas',
            'u_alamat',
            'u_no_hp',
            [
                'label'=>'Status',
                'attribute'=>'u_status',
                'format'=>'raw',
                'value'=>function($data){
                    return $data->u_status=='0' ? '<div class="btn btn-xs btn-danger">Tidak Aktif</div>' : '<div class="btn btn-xs btn-success">Aktif</div>';
                },
                'filter'=>['0'=>'Tidak Aktif','1'=>'Aktif']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'-',
                    'template' => '{edit}',
                    'buttons'=>[
                        'edit'=>function($url,$model){
                            return "<a href='#' data-id='".$model->u_id."' data-pjax='0' class='btn btn-sm btn-info btn-edit' title='edit status' data-toggle='tooltip'><i class='fa fa-edit'></i></a>"; 
                        }
                    ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>