<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title = 'Paket';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $('.btn-create').click(function(e){
        e.preventDefault();
        var btn=$(this);
        var htm=btn.html();
        formModal({url:'".Url::to(['form'])."',loading:{btn:btn,html:htm,txt:'Loading...'}});
    });
    function editBtn(){
        $('#grid-paket .table tbody tr td').on('click','.btn-edit',function(e){
            e.preventDefault();
            var id=$(this).attr('data-id');
            var btn=$(this);
            var htm=btn.html();
            formModal({url:'".Url::to(['form'])."',data:{id:id},loading:{btn:btn,html:htm}});
        });
    }
    function deleteBtn(){
        $('#grid-paket .table tbody tr td').on('click','.btn-delete',function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            var id=$(this).attr('data-id');
            if(confirm('Yakin hapus paket ?')){
                setBtnLoading(btn);
                $.ajax({
                    url:'".Url::to(['delete'])."',
                    type:'post',
                    dataType:'json',
                    data:{id:id},
                    success:function(result){
                        if(result.status){
                            successMsg(result.msg);
                            $.pjax.reload({container: '#pjax-paket', async: false});
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
<h3><?= $this->title ?></h3>
<p>
    <a href="#" class="btn btn-sm btn-success btn-create"><i class="fa fa-plus"></i> Paket Baru</a>
</p>
<?php Pjax::begin(['id'=>'pjax-paket']); ?>
<?= GridView::widget([
    'id'=>'grid-paket',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'nama',
        [
            'label'=>'Kelompok Paket',
            'attribute'=>'jenis_paket',
            'value'=>function($q){
                return $q->jenis_paket=='1' ? 'Umum' : ( $q->jenis_paket=='2' ? 'Instansi' : 'Umum/Instansi');
            },
            'filter'=>['1'=>'Umum','2'=>'Instansi','3'=>'Umum/Instansi']
        ],
        [
            'label'=>'Status',
            'attribute'=>'is_active',
            'format'=>'raw',
            'value'=>function($q){
                return $q->is_active=='1' ? '<div class="btn btn-xs btn-success">Aktif</div>' : '<div class="btn btn-xs btn-danger">Tidak Aktif</div>';
            },
            'filter'=>['1'=>'Aktif','0'=>'Tidak Aktif']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'-',
            'template' => '{view} {update} {delete}',
            'buttons'=>[
                'view'=>function($url,$model){
                    return "<a href='".Url::to(['view','id'=>$model->kode])."' data-pjax='0' class='btn btn-info btn-sm' title='kelola paket untuk pemeriksaan mcu' data-toggle='tooltip'><i class='fa fa-search'></i></a>"; 
                },
                'update'=>function($url,$model){
                    return "<a href='#' data-pjax='0' data-id='".$model->kode."' class='btn btn-primary btn-sm btn-edit' title='edit paket' data-toggle='tooltip'><i class='fa fa-edit'></i></a>";
                },
                'delete'=>function($url,$model){
                    return "<a href='#' class='btn btn-danger btn-sm btn-delete' data-id='".$model->kode."' title='hapus paket' data-pjax='0'  data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                },
            ]
        ],
    ],
]); ?>
<?php Pjax::end(); ?>