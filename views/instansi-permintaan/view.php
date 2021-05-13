<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\widgets\App;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
$this->title = strtoupper($model['up_nama']);
if(App::isDokter()){
    $this->registerJs("
        $('.btn-edit-status').click(function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            formModal({url:'".Url::to(['status-form'])."',data:{id:".$model['up_id']."},loading:{btn:btn,html:htm,txt:'Loading...'}});
        });
        $('.btn-create-jadwal').click(function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            formModal({url:'".Url::to(['jadwal-form'])."',data:{up_id:".$model['up_id']."},loading:{btn:btn,html:htm,txt:'Loading...'}});
        });
        $('.table-jadwal tbody tr td').on('click','.btn-edit',function(e){
            e.preventDefault();
            var id=$(this).attr('data-id');
            var btn=$(this);
            var htm=btn.html();
            formModal({url:'".Url::to(['jadwal-form'])."',data:{id:id},loading:{btn:btn,html:htm}});
        });
        $('.table-jadwal tbody tr td').on('click','.btn-delete',function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            var id=$(this).attr('data-id');
            if(confirm('Yakin hapus jadwal ?')){
                setBtnLoading(btn);
                $.ajax({
                    url:'".Url::to(['jadwal-delete'])."',
                    type:'post',
                    dataType:'json',
                    data:{id:id},
                    success:function(result){
                        if(result.status){
                            location.reload();
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
    ");
}
if(App::isInstansi()){
    $this->registerJs("
        $('.btn-create-peserta').click(function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            formModal({url:'".Url::to(['peserta-form'])."',data:{up:".$model['up_id']."},loading:{btn:btn,html:htm,txt:'Loading...'}});
        });
        function deleteBtn(){
            $('#grid-peserta .table tbody tr td').on('click','.btn-delete',function(e){
                e.preventDefault();
                var btn=$(this);
                var htm=btn.html();
                var id=$(this).attr('data-id');
                if(confirm('Yakin hapus peserta ?')){
                    setBtnLoading(btn);
                    $.ajax({
                        url:'".Url::to(['peserta-delete'])."',
                        type:'post',
                        dataType:'json',
                        data:{id:id},
                        success:function(result){
                            if(result.status){
                                successMsg(result.msg);
                                $.pjax.reload({container: '#pjax-peserta', async: false});
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
        deleteBtn();
        $(document).on('ready pjax:success',function(){
            deleteBtn();
        });
        $('.btn-form-excel').click(function(e){
            e.preventDefault();
            $('.form-excel').toggle();
        });
        $('.btn-upload-excel').click(function(e){
            e.preventDefault();
            var btn=$(this);
            var htm=btn.html();
            var form=$('.form-upload-excel');
            var el=form.find('input[name=\'import\']');
            var file=el[0].files[0];
            setBtnLoading(btn,'Uploading...');
            if(file){
                var formData = new FormData();
                formData.append('import', file);
                formData.append('up', ".$model['up_id'].");
                $.ajax({
                    url:'".Url::to(['import'])."',
                    type:'post',
                    dataType:'json',
                    data:formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    success:function(result){
                        if(result.status){
                            successMsg(result.msg);
                            setTimeout(function(){ location.reload(); },1500);
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
            }else{
                resetBtnLoading(btn,htm);
                errorMsg('Terjadi kesalahan dalam memilih file, silahkan coba kembali');
            }
        });
    ");
}
if(App::isInstansi() || App::isRm()){
    $this->registerJs("
    function editBtn(){
        $('#grid-peserta .table tbody tr td').on('click','.btn-edit',function(e){
            e.preventDefault();
            var id=$(this).attr('data-id');
            var up=$(this).attr('data-up');
            var btn=$(this);
            var htm=btn.html();
            formModal({url:'".Url::to([App::isInstansi() ? 'peserta-form' : 'peserta-verify-form'])."',data:{id:id,up:up},loading:{btn:btn,html:htm}});
        });
    }
    editBtn();
    $(document).on('ready pjax:success',function(){
        editBtn();
    });
    ");
}
?>
<h3><b><?= $this->title ?></b></h3>
<p>
    <a href="<?php echo Url::to(['index']) ?>" class="btn btn-default btn-sm"><i class='fa fa-arrow-left'></i> Kembali</a>
    <?php
    if(App::isDokter()){
        ?><a href="#" class="btn btn-default btn-sm btn-primary btn-edit-status"><i class='fa fa-edit'></i> Status</a><?php
    }
    ?>
</p>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label'=>'Nama Instansi',
            'value'=>function($d){
                return $d['user']!=NULL ? $d['user']['u_nama_depan'] : NULL;
            }
        ],
        [
            'label'=>'Nama Permintaan',
            'value'=>$model['up_nama']
        ],
        [
            'label'=>'Total Peserta',
            'value'=>$model['up_total_peserta'].' Peserta'
        ],
        [
            'label'=>'Tanggal Mulai Pemeriksaan',
            'value'=>date('d-m-Y',strtotime($model['up_tgl_mulai']))
        ],
        [
            'label'=>'Tanggal Selesai Pemeriksaan',
            'value'=>date('d-m-Y',strtotime($model['up_tgl_selesai']))
        ],
        [
            'label'=>'Status Pengajuan Pemeriksaan',
            'format'=>'raw',
            'value'=>function($d){
                return $d['up_status']==1 ? "<div class='btn btn-xs btn-success'>Disetujui</div>" : "<div class='btn btn-xs btn-danger'>Belum Disetujui</div>";
            }
        ],
        [
            'label'=>'Debitur',
            'visible'=>App::isDokter() ? true : false,
            'value'=>function($d){
                return $d['debitur']!=NULL ? $d['debitur']['d_nama'] : '';
            }
        ],
        [
            'label'=>'Jenis Pemeriksaan',
            'value'=>function($d){
                return $d['jenismcu']!=NULL ? $d['jenismcu']['jm_nama'] : '';
            }
        ],
        [
            'label'=>'Keterangan Status Pengajuan Pemeriksaan',
            'visible'=>$model['up_status_ket']!=NULL ? true : false,
            'value'=>$model['up_status_ket']
        ],
        [
            'label'=>'Paket Pemeriksaan',
            'format'=>'raw',
            'value'=>function($q){
                $tmp=[];
                $h="
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Kode Paket</th>
                            <th>Nama Paket</th>
                        </tr>
                    </thead>
                    <tbody>
                ";
                if(count($q['paketpemeriksaan'])>0){
                    $h.="<tbody>";
                    foreach($q['paketpemeriksaan'] as $p){
                        if($p['paket']!=NULL){
                            $h.="
                            <tr>
                                <td>".$p['paket']['kode']."</td>
                                <td>".$p['paket']['nama']."</td>
                            </tr>
                            ";
                        }
                    }
                    $h.="<tbody>";
                }else{
                    $h.="<tbody><tr><td colspan='2'>-</td></tr></tbody>";
                }
                $h.="</table>";
                return $h;
            }
        ]
    ],
]) ?>
<h4><b>Jadwal Pemeriksaan</b></h4>
<?php
if(App::isDokter()){
    ?>
    <p>
        <a href="#" class='btn btn-sm btn-success btn-create-jadwal'><i class="fa fa-plus"></i> Tambah Jadwal</a>
    </p>
    <?php
}
?>
<table class="table table-hover table-bordered table-jadwal">
    <thead>
        <tr>    
            <th>Kode Jadwal Pemeriksaan</th>
            <th>Tanggal</th>
            <th>Kuota Peserta Per Hari</th>
            <?php echo App::isDokter() ? '<th></th>' : '' ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if(count($model['jadwal'])>0){
            foreach($model['jadwal'] as $j){
                ?>
                <tr>
                    <td><?php echo $j['upj_id']; ?></td>
                    <td><?php echo date('d-m-Y',strtotime($j['upj_tgl'])) ?></td>
                    <td><?php echo $j['upj_kuota'] ?> peserta</td>
                    <?php
                    if(App::isDokter()){
                        ?>
                        <td>
                            <a href="<?php echo url::to(['pdf','id'=>$j['upj_id']]); ?>" class="btn btn-sm btn-info" title="cetak pdf" target="_blank"><i class="fa fa-print"></i></a>
                            <a href="#" class="btn btn-sm btn-primary btn-edit" data-id='<?php echo $j['upj_id']; ?>' title="edit jadwal"><i class="fa fa-edit"></i></a>
                            <a href="#" class="btn btn-sm btn-danger btn-delete" data-id='<?php echo $j['upj_id']; ?>' title="hapus jadwal"><i class="fa fa-trash"></i></a>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
        }else{
            ?><tr><td colspan="3">Jadwal belum tersedia</td></tr><?php
        }
        ?>
    </tbody>
</table>
<h4><b>Peserta Pemeriksaan Medical Check Up (MCU)</b></h4>
<?php
if(App::isInstansi() && $model['up_status']==1 && count($model['jadwal'])>0){
    ?>
    <p>
        <a href="#" class='btn btn-sm btn-success btn-create-peserta'><i class="fa fa-plus"></i> Tambah Peserta</a>
        <a href="#" class='btn btn-sm btn-primary btn-form-excel'><i class="fa fa-file-excel-o"></i> Upload Excel</a>
    </p>
    <div class="form-excel well" style="display:none;">
        <form class="form-upload-excel">
            <div class="form-group">
                <label for="import">Import Excel</label>
                <input type="file" name="import" id="import" required="required" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
            </div>
            <a href="<?php echo Url::base(); ?>/files/template.xlsx" class="btn btn-info btn-xs"><i class="fa fa-file-excel-o"></i> Template Excel</a>
            <button type="button" class="btn btn-xs btn-success btn-upload-excel"><i class="fa fa-upload"></i> Upload Excel</button><br>
            <strong>Tambah peserta menggunakan file excel. WAJIB MENGGUNAKAN TEMPLATE EXCEL. Sebelum upload, silahkan baca <a href="<?php echo Url::to(['instansi-informasi/list']); ?>" target='_blank'>informasi</a></strong>
        </form>
    </div>
    <?php
}
Pjax::begin(['id'=>'pjax-peserta']); ?>
<?= GridView::widget([
    'id'=>'grid-peserta',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions'=>function ($model, $key, $index, $grid){
        $status=NULL;
        $title=NULL;
        if($model->u_approve_status=='0'){
            $status='danger';
            $title='Tidak Disetujui';
        }elseif($model->u_approve_status=='1'){
            $status='warning';
            $title='Direvisi';
        }elseif($model->u_approve_status=='2'){
            $status='success';
            $title='Disetujui';
        }
        return ['class'=>$status,'title'=>$title];
    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute'=>'u_rm',
            'visible'=>App::isRm() || App::isDokter() ? true : false,
        ],
        'u_nik',
        'u_nama_depan',
        [
            'label'=>'Tanggal Lahir',
            'attribute'=>'u_tgl_lahir',
            'value'=>function($q){
                return $q->u_tgl_lahir!=NULL ? date('d-m-Y',strtotime($q->u_tgl_lahir)) : '';
            }
        ],
        [
            'label'=>'Jadwal Pemeriksaan',
            'attribute'=>'u_upj_id',
            'value'=>function($d){
                return $d->jadwalperiksa!=NULL ? date('d-m-Y',strtotime($d->jadwalperiksa->upj_tgl)) : '';
            },
            'filter'=>ArrayHelper::map(array_map(function($q){
                return ['id'=>$q['upj_id'],'tgl'=>date('d-m-Y',strtotime($q['upj_tgl']))];
            },$model['jadwal']),'id','tgl')
        ],
        [
            'label'=>'Paket',
            'attribute'=>'u_paket_id',
            'value'=>function($q){
                return $q->paket!=NULL ? $q->paket->nama : '';
            },
            'filter'=>ArrayHelper::map($paket,'kode','nama')
        ],
        [
            'attribute'=>'u_approve_status',
            'format'=>'raw',
            'value'=>function($q){
                if($q->u_approve_status=='0'){
                    $s= 'Tidak Disetujui';
                }elseif($q->u_approve_status=='1'){
                    $s= 'Revisi';
                }elseif($q->u_approve_status=='2'){
                    $s= 'Disetujui';
                }elseif($q->u_approve_status=='3'){
                    $s='Baru';
                }
                if($q->u_approve_status!='2'){
                    if(!empty($q->u_approve_ket)){
                        $s.='<br><b>Ket : '.$q->u_approve_ket.'</b>';
                    }
                }
                return $s;
            },
            'filter'=>['0'=>'Tidak Disetujui','Revisi','Disetujui','Baru'],
        ],
        [
            'attribute'=>'u_is_pasien_baru',
            'visible'=>App::isRm() ? true : false,
            'value'=>function($q){
                return $q->u_is_pasien_baru!=NULL ? ( $q->u_is_pasien_baru=='y' ? 'Pasien Baru' : 'Pasien Lama' ) : '-';
            },
            'filter'=>['y'=>'Pasien Baru','n'=>'Pasien Lama']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'-',
            'headerOptions'=>['width'=>70],
            'template' => '{view} {update} {delete} {biodata}',
            'buttons'=>[
                'view'=>function($url,$m) use($model){
                    if(App::isDokter()){
                        return "<a href='".Url::to(['peserta-view','permintaan'=>$model['up_id'],'id'=>$m->u_id])."' data-pjax='0' class='btn btn-info btn-xs' title='detail peserta'  data-toggle='tooltip'><i class='fa fa-list'></i></a>";
                    }
                },
                'update'=>function($url,$m) use($model){
                    if(App::isInstansi() && $m->u_finish_at==NULL){
                        return "<a href='#' data-pjax='0' data-up='".$model['up_id']."' data-id='".$m->u_id."' class='btn btn-primary btn-xs btn-edit' title='edit peserta'  data-toggle='tooltip'><i class='fa fa-edit'></i></a>";
                    }elseif(App::isRm()){
                        return "<a href='".Url::to(['peserta-detail','id'=>$m->u_id,'up'=>$model['up_id']])."' data-pjax='0' class='btn btn-info btn-sm' title='lihat detail peserta' target='_blank' data-toggle='tooltip'><i class='fa fa-search'></i></a>";
                    }
                },
                'delete'=>function($url,$model){
                    if( App::isInstansi() && $model->u_biodata_finish_at==NULL && $model->u_approve_status!='2'){
                        return "<a href='#' class='btn btn-danger btn-xs btn-delete' data-id='".$model->u_id."' title='hapus peserta' data-pjax='0'  data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                    }
                },
                'biodata'=>function($url,$m) use($model){
                    if(App::isRm()){
                        return "<a href='".Url::to(['edit','id'=>$m['u_id'],'up'=>$model['up_id']])."' data-pjax='0' title='edit' class='btn btn-primary btn-sm' target='_blank'><i class='fa fa-edit'></i></a>";
                    }
                }
            ]
        ],
    ],
]); ?>
<?php Pjax::end();