<?php
use app\widgets\App;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = 'Berkas Peserta';

if(!$isRegClose){
    $this->registerJs("
    $(document).find('input[name=\'berkas\']').on('change',function(){
        var el=$(this);
        var el_label=el.siblings('label');
        var label=el_label.html();
        var id=el.attr('data-id');

        el.attr('disabled',true);
        el_label.html('<i class=\'fa fa-refresh fa-spin\'></i> Uploading...');
        
        var file = el[0].files[0];
        if(file){
            var formData = new FormData();
            formData.append('berkas', file);
            formData.append('id',id);
            $.ajax({
                url:'".Url::to(['berkas-upload'])."',
                type:'post',
                dataType:'json',
                data:formData,
                contentType: false,
                cache: false,
                processData:false,
                success:function(result){
                    if(result.status){
                        toastr['success'](result.msg);
                        var btn_file='<a href=\'".Url::to(['file'])."?data='+result.id+'\' target=\'_blank\' class=\'btn btn-primary\'><i class=\'fa fa-file\'></i> Lihat Berkas</a>';
                        console.log(el.parent().parent().parent());
                        el.parent().parent().parent().find('.td-btn').html(btn_file);
                        $('.btn-next').show('hide');
                        $('.progress-berkas').removeClass('inactive progress-bar-warning').addClass('active progress-bar-success').find('.status-icon').html('<i class=\'fa fa-check-circle\'></i>');
                    }else{
                        if(typeof result.msg =='object'){
                            $.each(result.msg,function(i,v){
                                toastr['error'](v);
                            });
                        }else{
                            toastr['error'](result.msg);
                        }
                    }
                    el.removeAttr('disabled').val('');
                    el_label.html(label);
                    console.log(result);
                },
                error:function(xhr,status,error){
                    el.removeAttr('disabled').val('');
                    el_label.html(label);
                    toastr['error'](error);
                }
            });
        }else{
            toastr['error']('Terjadi kesalahan dalam memilih file, silahkan coba kembali');
        }
    });
    ");
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php echo $this->render('timeline'); ?>
            <h3 class="page-header text-center">.: Berkas :.</h3>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Upload Berkas</th>
                        <th align="center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(count($berkas)>0){
                        foreach($berkas as $sb){
                            ?>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="berkas<?php echo $sb['b_id']; ?>"><?php echo $sb['b_nama'] ?></label>
                                        <?php
                                        if(!$isRegClose){
                                            ?>
                                            <input type="file" data-title='<?php echo $sb['b_nama'] ?>' data-id="<?php echo $sb['b_id']; ?>" id="file<?php echo $sb['b_id']; ?>" name="berkas">
                                        <?php
                                        }
                                        ?>
                                    </div>                                
                                </td>
                                <td align="center" class='td-btn' style="vertical-align:middle;">
                                    <?php
                                    if(!$isRegClose){
                                        if($sb['userberkas']!=NULL){
                                            ?><a href="<?php echo Url::to(['file','data'=>$sb['b_id']]) ?>" target="_blank" class="btn btn-primary"><i class="fa fa-file"></i> Lihat Berkas</a><?php
                                        }
                                    }else{
                                        $isFile=false;
                                        if($sb['userberkas']!=NULL){
                                            $isFile=true;
                                        }
                                        ?><button type="button" class="btn btn-sm <?php echo $isFile ? 'btn-primary' : 'btn-danger' ?>"><?php echo $isFile ? '<i class="fa fa-check-circle"></i> Sudah Diupload' : '<i class="fa fa-close"></i> Belum Diupload' ?></button><?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <a href="<?php echo Url::to(['biodata']); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Sebelumnya (Biodata)</a>
            <span class="btn-next" style="<?php echo $user->u_berkas_finish_at==NULL ? "display:none;" : "" ?>"><a href="<?php echo Url::to(['kuisioner-sosial']); ?>" class="btn btn-default"><i class="fa fa-arrow-right"></i> Selanjutnya (Kuisioner)</a>
        </div>
    </div>
</div>