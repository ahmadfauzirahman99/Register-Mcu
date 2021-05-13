paceOptions = {
    elements: false,
    ajax:true,
    restartOnPushState: false,
    restartOnRequestAfter: true,
    // minTime:50000,
    // ghostTime:120000,
    // maxProgressPerFrame:50
}
$('[data-toggle="popover"]').popover({
    container:'body'
});
$(document).ajaxStart(function() { Pace.restart(); });
$(document).ajaxStop(function() { Pace.stop(); });

function setBtnLoading(btn,txt='')
{
    btn.html('<i class="fa fa-refresh fa-spin fa-fw"></i> '+txt).attr('disabled',true);
}
function resetBtnLoading(btn,htm)
{
    btn.html(htm).removeAttr('disabled');
}
function errorMsg(txt){
    if(typeof txt =='object'){
        $.each(txt,function(i,v){
            toastr['error'](v);
        });
    }else{
        toastr['error'](txt);
    }
    
}
function successMsg(txt){
    toastr['success'](txt);
}
function formModal(obj){
    if(obj.loading){
        setBtnLoading(obj.loading.btn,obj.loading.txt?obj.loading.txt:'');
    }
    $.ajax({
        url:obj.url,
        type:'post',
        dataType:'html',
        data:obj.data?obj.data:'',
        success:function(result){
            $(obj.modal?obj.modal:'#mymodal').html(result).modal('show');
            if(obj.loading){
                resetBtnLoading(obj.loading.btn,obj.loading.html);
            }
        },
        error:function(xhr,status,error){
            errorMsg(error);
            if(obj.loading){
                resetBtnLoading(obj.loading.btn,obj.loading.html);
            }
        }
    });
}
function disabledDate(date){
    var day = date.getDay();
    if(day == 0 || day == 6){
        return [false];
    }
    var datestring = $.datepicker.formatDate("dd-mm-yy",date);
    for (var i = 0; i < enableDate.length; i++) {
        if (enableDate[i] == datestring) {             
            return [false];
        }
    }
    return [true];
}