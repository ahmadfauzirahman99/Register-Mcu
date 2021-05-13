<?php
use app\widgets\App;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
$this->title = 'Permintaan Pemeriksaan Medical Check Up (MCU)';
$this->registerJs("
    $('.btn-create').click(function(e){
        e.preventDefault();
        var btn=$(this);
        var htm=btn.html();
        formModal({url:'".Url::to(['form'])."',loading:{btn:btn,html:htm,txt:'Loading...'}});
    });
    function editBtn(){
        $('#grid-permintaan .table tbody tr td').on('click','.btn-edit',function(e){
            e.preventDefault();
            var id=$(this).attr('data-id');
            var btn=$(this);
            var htm=btn.html();
            formModal({url:'".Url::to(['form'])."',data:{id:id},loading:{btn:btn,html:htm}});
        });
    }
    function deleteBtn(){
        $('#grid-permintaan .table tbody tr td').on('click','.btn-delete',function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            var id=$(this).attr('data-id');
            if(confirm('Yakin hapus permintaan ?')){
                setBtnLoading(btn);
                $.ajax({
                    url:'".Url::to(['delete'])."',
                    type:'post',
                    dataType:'json',
                    data:{id:id},
                    success:function(result){
                        if(result.status){
                            successMsg(result.msg);
                            $.pjax.reload({container: '#pjax-permintaan', async: false});
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
<?php
if(App::isInstansi()){
    ?>
    <p>
        <a href="#" class="btn btn-sm btn-success btn-create"><i class="fa fa-plus"></i> Permintaan Baru</a>
    </p>
    <?php 
}
Pjax::begin(['id'=>'pjax-permintaan']); ?>
<?= GridView::widget([
    'id'=>'grid-permintaan',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label'=>'Nama Instansi',
            'attribute'=>'instansi',
            // 'visible'=>App::isDokter() ? true : false,
            'value'=>function($d){
                return $d->user!=NULL ? $d->user->u_nama_depan : NULL;
            }
        ],
        'up_nama',
        [
            'label'=>'Tgl Mulai Periksa',
            'attribute'=>'up_tgl_mulai',
            'value'=>function($data){
                return $data->up_tgl_mulai!=NULL ? date('d-m-Y',strtotime($data->up_tgl_mulai)) : '';
            },
            'filter'=>DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'up_tgl_mulai',
                'options'=>['class'=>'form-control','id'=>'up_tgl_mulai'],
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'autoclose'=>true,
                    'changeYear'=>true,
                    'changeMonth'=>true,
                ]
            ])
        ],
        [
            'label'=>'Tgl Selesai Periksa',
            'attribute'=>'up_tgl_selesai',
            'value'=>function($data){
                return $data->up_tgl_selesai!=NULL ? date('d-m-Y',strtotime($data->up_tgl_selesai)) : '';
            },
            'filter'=>DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'up_tgl_selesai',
                'options'=>['class'=>'form-control','id'=>'up_tgl_selesai'],
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
            'attribute'=>'up_jenis_mcu_id',
            'format'=>'raw',
            'value'=>function($d){
                return $d->jenismcu!=NULL ? $d->jenismcu->jm_nama : "";
            },
            'filter'=>ArrayHelper::map($jenis_mcu,'jm_id','jm_nama')
        ],
        [
            'label'=>'Status Permintaan',
            'attribute'=>'up_status',
            'format'=>'raw',
            'value'=>function($d){
                return $d->up_status=='1' ? "<div class='btn btn-xs btn-success'>Sudah Disetujui</div>" : "<div class='btn btn-xs btn-danger'>Belum Disetujui</div>";
            },
            'filter'=>['0'=>'Belum Disetujui','1'=>'Sudah Disetujui']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'-',
            'template' => '{view}<br>{update}<br>{delete}',
            'buttons'=>[
                'view'=>function($url,$model){
                    return "<a href='".Url::to(['view','id'=>$model->up_id ])."' data-pjax='0' class='btn btn-info btn-xs' title='kelola peserta untuk pemeriksaan mcu' data-toggle='tooltip'><i class='fa fa-user'></i> peserta</a>"; 
                },
                'update'=>function($url,$model){
                    if(App::isInstansi() && $model->up_status!=1){
                        return "<a href='#' data-pjax='0' data-id='".$model->up_id."' class='btn btn-primary btn-xs btn-edit' title='edit permintaan' data-toggle='tooltip'><i class='fa fa-edit'></i> edit permintaan</a>";
                    }
                },
                'delete'=>function($url,$model){
                    if(App::isInstansi() && $model->up_status!=1){
                        return "<a href='#' class='btn btn-danger btn-xs btn-delete' data-id='".$model->up_id."' title='hapus permintaan' data-pjax='0'  data-toggle='tooltip'><i class='fa fa-trash'></i> hapus permintaan</a>";
                    }
                },
            ]
        ],
    ],
]); ?>
<?php Pjax::end(); ?>