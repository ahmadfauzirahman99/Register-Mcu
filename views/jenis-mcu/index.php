<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title = 'Jenis Pemeriksaan MCU';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
function editBtn(){
    $('#grid-jenis-mcu .table tbody tr td').on('click','.btn-edit',function(e){
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
<div class="jenis-mcu-index">
    <h3><strong><?= $this->title ?></strong></h3>
    <?php Pjax::begin(['id'=>'pjax-jenis-mcu']); ?>
    <?= GridView::widget([
        'id'=>'grid-jenis-mcu',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'jm_nama',
            'jm_ket:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'-',
                'template' => '{edit}',
                'buttons'=>[
                    'edit'=>function($url,$model){
                        return "<a href='#' data-id='".$model->jm_id."' data-pjax='0' class='btn btn-sm btn-info btn-edit' title='edit status' data-toggle='tooltip'><i class='fa fa-edit'></i></a>"; 
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
