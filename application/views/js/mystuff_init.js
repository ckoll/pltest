$(function(){
    $('.delete_photo').click(function(){
        var id = $(this).data('id')
        $.post('/upload/ajax',{
            'func': 'del_uploaded_img',
            'id': id
        },function(){
            document.location = document.URL;
        },'json');
        return false;
    });
    $('.my_photo').click(function(){
        var url = $(this).data('url');
        document.location = url;
    });
    
    
})