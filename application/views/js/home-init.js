$(function(){
    $('input[type=text],input[type=password]').focus(function(){
        if($(this).prev().is('label')){
            $(this).prev().fadeOut(200)
        }
    }).blur(function(){
        if($(this).prev().is('label') && !$(this).val()){
            $(this).prev().fadeIn(200)
        }
    })
    
    $('form label').click(function(){
        $(this).next().focus()
    })
    
    $('#resendemail').click(function(){
        $('#resendemail').parent().html('<img src="/images/loading.gif">')
        var usename = $('input[name=username]').val()
        $.post('/resendemail',{
            'send': 1,
            'email': usename
        },function(){
            document.location = "/resendemail?sended=1";
        })
        return false;
    })
})