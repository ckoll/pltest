$(function(){
    $('.delete_photo').click(function(){
        if (confirm('Are you sure you want to delete this photo?')) {
            var id = $(this).data('id')
            $.post('/upload/ajax',{
                'func': 'del_uploaded_img',
                'id': id
            },function(){
                document.location = document.URL;
            },'json');
        }
        return false;
    });
    $('.my_photo').click(function(){
        var url = $(this).data('url');
        document.location = url;
    });
    
    
})