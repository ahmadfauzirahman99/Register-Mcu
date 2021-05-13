<?php
use yii\helpers\Url;
use yii\widgets\DetailView;
$this->title = $model->nama;
$this->registerjs("
$('.btn-create-tindakan').click(function(e){
    e.preventDefault();
    var btn=$(this);
    var htm=btn.html();
    formModal({url:'".Url::to(['tindakan-form'])."',data:{p:".$model->kode."},loading:{btn:btn,html:htm,txt:'Loading...'}});
});
$('.tb-tindakan tbody tr td').on('click','.btn-edit',function(e){
    e.preventDefault();
    var btn=$(this);
    var htm=btn.html();
    var id=btn.attr('data-id');
    formModal({url:'".Url::to(['tindakan-form'])."',data:{id:id},loading:{btn:btn,html:htm}});
});
$('.tb-tindakan tbody tr td').on('click','.btn-delete',function(e){
    e.preventDefault();
    var btn=$(this);
    var htm=btn.html();
    var id=btn.attr('data-id');
    if(id){
        if(confirm('Yakin hapus tindakan ?')){
            setBtnLoading(btn);
            $.ajax({
                url:'".Url::to(['tindakan-delete'])."',
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
    }
});
");
?>
<h3><?= $this->title ?></h3>
<p>
    <a href="<?php echo Url::to(['index']) ?>" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> kembali</a>
</p>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'nama',
        [
            'label'=>'Status',
            'format'=>'raw',
            'value'=>function($q){
                return $q->is_active=='1' ? '<div class="btn btn-xs btn-success">Aktif</div>' : '<div class="btn btn-xs btn-danger">Tidak Aktif</div>';
            }
        ],
        [
            'label'=>'Jenis Paket',
            'format'=>'raw',
            'value'=>function($q){
                return $q->jenis_paket=='1' ? 'Umum' : 'Instansi';
            }
        ]
    ],
]) ?>
<h3>Tindakan Paket <?php echo $model->nama; ?></h3>
<p>
    <a href="#" class="btn btn-sm btn-primary btn-create-tindakan"><i class="fa fa-plus"></i> Tambah Tindakan</a>
</p>
<table class="table table-bordered table-hover tb-tindakan">
    <thead>
        <tr>
            <th>No.</th>
            <th>Tindakan</th>
            <th>Harga</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(count($tindakan)>0){
            $no=1;
            $total=0;
            foreach($tindakan as $t){
                $total+=$t['harga'];
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $t['nama_tindakan']; ?></td>
                    <td align="right"><?php echo number_format($t['harga'],0,',','.'); ?></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary btn-edit" data-id="<?php echo $t['id']; ?>" title="edit tindakan"><i class="fa fa-edit"></i></a>
                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="<?php echo $t['id']; ?>" title="hapus tindakan"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php
                $no++;
            }
            ?>
            <tr>
                <td colspan="2" align="center">Total</td>
                <td align="right"><?php echo number_format($total,0,',','.'); ?></td>
                <td></td>
            </tr>
            <?php
        }else{
            ?>
            <tr>
                <td colspan="4">Tindakan belum tersedia</td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>