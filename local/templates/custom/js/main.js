$(document).ready(function (){
    $('#form-callback').on('submit',function (e){
        $.ajax({
            method: "POST",
            url:"/ajax/callback.php",
            data: $(this).serialize(),
            dataType: 'json',
            success: function (result){
                if(result.status=='success'){
                    $('#form-callback').html('<div class="success">'+result.message+'</div>');
                }
                if(result.status=='error'){
                    $('#form-callback .info-box').html('<div class="error">'+result.message+'</div>');
                }
            }
        });
        e.preventDefault();
    });
});