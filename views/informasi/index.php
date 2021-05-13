<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title = 'Informasi';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $('.btn-create').click(function(e){
        e.preventDefault();
        var btn=$(this);
        var htm=btn.html();
        formModal({url:'".Url::to(['form'])."',loading:{btn:btn,html:htm,txt:'Loading...'}});
    });
    function editBtn(){
        $('#grid-informasi .table tbody tr td').on('click','.btn-edit',function(e){
            e.preventDefault();
            var id=$(this).attr('data-id');
            var btn=$(this);
            var htm=btn.html();
            formModal({url:'".Url::to(['form'])."',data:{id:id},loading:{btn:btn,html:htm}});
        });
    }
    function deleteBtn(){
        $('#grid-informasi .table tbody tr td').on('click','.btn-delete',function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            var id=$(this).attr('data-id');
            if(confirm('Yakin hapus informasi ?')){
                setBtnLoading(btn);
                $.ajax({
                    url:'".Url::to(['delete'])."',
                    type:'post',
                    dataType:'json',
                    data:{id:id},
                    success:function(result){
                        if(result.status){
                            successMsg(result.msg);
                            $.pjax.reload({container: '#pjax-informasi', async: false});
                        }else{
                            errorMsg(result.msg);
                        }
                        resetBtnLoading(btn,htm);
                    },
                    error:function(xhr,status,error){
                        errorMsg(error);
                        resetBtnLoading(btn,htm);
                    }
                });
            }
            return false;
        });
    }
    editBtn();
    deleteBtn();
    $(document).on('ready pjax:success',function(){
        editBtn();
        deleteBtn();
    });
");
?>
<div class="informasi-index">
    <h3><?= Html::encode($this->title) ?></h3>
    <p>
    <a href="#" class="btn btn-success btn-sm btn-create"><i class="icon-plus"></i> Informasi Baru</a>
    </p>
    <?php Pjax::begin(['id'=>'pjax-informasi']); ?>
    <?= GridView::widget([
        'id'=>'grid-informasi',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'Jenis Informasi',
                'attribute'=>'i_jenis',
                'value'=>function($q){
                    return $q->i_jenis=='1' ? 'Informasi Pendaftaran Umum' : ( $q->i_jenis=='2' ? 'Disclaimer' : 'Informasi Pendaftaran Instansi');
                },
                'filter'=>['1'=>'Informasi Pendaftaran Umum','3'=>'Informasi Pendaftaran Instansi','2'=>'Disclaimer']
            ],
            [
                'label'=>'Status Informasi',
                'attribute'=>'i_status',
                'format'=>'raw',
                'value'=>function($q){
                    return $q->i_status=='1' ? '<div class="btn btn-xs btn-success btn-block">Aktif</div>' : '<div class="btn btn-xs btn-danger btn-block">Tidak Aktif</div>';
                },
                'filter'=>['1'=>'Aktif','0'=>'Tidak Aktif']
            ],
            [
                'label'=>'Informasi',
                'attribute'=>'i_info',
                'format'=>'raw',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'-',
                'headerOptions'=>['width'=>100],
                'template' => '{update} {delete}',
                'buttons'=>[
                    'update'=>function($url,$model){
                        return "<a href='#' data-pjax='0' data-id='".$model->i_id."' class='btn btn-primary btn-sm btn-edit' title='edit informasi'  data-toggle='tooltip'><i class='fa fa-edit'></i></a>";
                    },
                    'delete'=>function($url,$model){
                        return "<a href='#' class='btn btn-danger btn-sm btn-delete' data-id='".$model->i_id."' title='hapus informasi' data-pjax='0'  data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>