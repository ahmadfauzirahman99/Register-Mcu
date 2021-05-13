<?php 
use yii\helpers\Url;
$this->title="Upload Berkas";
$this->registerJs("
$(document).find('input[name=\'berkas\']').on('change',function(){
    var el=$(this);
    var el_label=el.siblings('label');
    var label=el_label.html();
    var id=el.attr('data-id');

    setBtnLoading(el,'Uploading...');
    
    var file = el[0].files[0];
    if(file){
        var formData = new FormData();
        formData.append('berkas', file);
        formData.append('id',id);
        $.ajax({
            url:'".Url::to(['berkas-save'])."',
            type:'post',
            dataType:'json',
            data:formData,
            contentType: false,
            cache: false,
            processData:false,
            success:function(result){
                if(result.status){
                    toastr['success'](result.msg);
                    var btn_file='<a href=\'".Url::to(['get-berkas'])."?data='+result.id+'\' target=\'_blank\' class=\'btn btn-primary\'><i class=\'fa fa-file\'></i> Lihat Berkas</a>';
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
?>
<?php echo $this->render('permintaan_timeline'); ?>
<div style="margin-bottom:10px; font-size:18px; text-align:center; border-bottom:1px solid #000;"><?php echo strtoupper($this->title); ?></div>

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
                            if(!$is_reg_close){
                                ?>
                                <input type="file" data-title='<?php echo $sb['b_nama'] ?>' data-id="<?php echo $sb['b_id']; ?>" id="file<?php echo $sb['b_id']; ?>" name="berkas" accept="image/jpeg,application/pdf">
                                <?php
                            }
                            ?>
                        </div>                                
                    </td>
                    <td align="center" class='td-btn' style="vertical-align:middle;">
                        <?php
                        if(!$is_reg_close){
                            if($sb['userberkas']!=NULL){
                                ?><a href="<?php echo Url::to(['get-berkas','data'=>$sb['b_id']]) ?>" target="_blank" class="btn btn-primary"><i class="fa fa-file"></i> Lihat Berkas</a><?php
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
<a href="<?php echo Url::to(['daftar']); ?>" class="btn btn-default"><i class="fa fa-angle-left"></i> Sebelumnya (Daftar)</a>
<span class="btn-next" style="<?php echo $user['u_berkas_finish_at']==NULL ? "display:none;" : "" ?>"><a href="<?php echo Url::to(['kuisioner-sosial']); ?>" class="btn btn-default"><i class="fa fa-angle-right"></i> Selanjutnya (Kuisioner)</a>