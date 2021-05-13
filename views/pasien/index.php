<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
$this->title = 'Pasien';
$this->registerJs("
function editBtn(){
    $('#grid-pasien .table tbody tr td').on('click','.btn-view',function(e){
        e.preventDefault();
        var id=$(this).attr('data-id');
        var btn=$(this);
        var htm=btn.html();
        formModal({url:'".Url::to(['view'])."',data:{id:id},loading:{btn:btn,html:htm}});
    });
}
editBtn();
$(document).on('ready pjax:success',function(){
    editBtn();
});
$('.btn-search').click(function(e){
    e.preventDefault();
    $('.wrap-search').toggle();
});
");
?>
<div class="user-index">
    <h3><strong><?= $this->title ?></strong></h3>
    <a href="#" class="btn btn-sm btn-info btn-search"><i class="fa fa-search"></i> Pencarian</a>
    <?php Pjax::begin(['id'=>'pjax-pasien']); ?>
    <div class="wrap-search" style="display:none;"><?php echo $this->render('search',['model'=>$searchModel,'agama'=>$agama,]); ?></div>
    <?= GridView::widget([
		'id'=>'grid-pasien',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'NO_PASIEN',
            'NAMA',
            'TP_LAHIR',
            [
                'label'=>'Tanggal Lahir',
                'attribute'=>'TGL_LAHIR',
                'value'=>function($q){
                    return date('d-m-Y',strtotime($q->TGL_LAHIR));
                },
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'TGL_LAHIR',
                    'options'=>['class'=>'form-control','id'=>'TGL_LAHIR'],
                    'dateFormat' => 'dd-MM-yyyy',
                    'clientOptions'=>[
                        'maxDate'=>date('d-m-Y'),
                        'autoclose'=>true,
                        'changeYear'=>true,
                        'changeMonth'=>true,
                    ]
                ])
            ],
            'ALAMAT',
            'NOIDENTITAS',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'-',
                'template' => '{edit}',
                'buttons'=>[
                    'edit'=>function($url,$model){
                        return "<a href='#' data-id='".$model->NO_PASIEN."' data-pjax='0' class='btn btn-sm btn-info btn-view' title='detail pasien' data-toggle='tooltip'><i class='fa fa-search'></i></a>"; 
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>