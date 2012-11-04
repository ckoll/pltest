$(function(){
    $('.delete_photo').click(function(){
        var id = $(this).data('id')
        $.post('/upload/ajax',{
            'func': 'del_uploaded_img',
            'id': id
        },function(){
            document.location = "/upload"
        },'json')
    })
    
    
})