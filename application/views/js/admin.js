$(function(){
    $('input[name=add_gift]').click(function(){
        if(!$('input[name=img]').val()){
            $('.err').text('Select gift image').show()
            return false;
        }else if(!$('input[name=name]').val()){
            $('.err').text('Fill Gift Name').show()
            return false;
        }else if(!$('input[name=price]').val()){
            $('.err').text('Fill Gift Price').show()
            return false;
        }else{
            $(this).parent().submit()
        }        
    })
    
    
    $('img[src$="del.gif"]').click(function (){
        if(confirm('Remove?')) {
            return true;
        }else{
            return false;
        }
    })
    
    $('textarea.tinymce').tinymce({
        script_url : '/js/tiny_mce/tiny_mce.js',
        theme : "advanced",
        plugins : "autolink,style,media,searchreplace,paste,directionality,fullscreen,advlist,contextmenu,emotions",
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,fontsizeselect,pasteword,bullist,numlist,|,outdent,indent,|,link,unlink,image,code,|,emotions,forecolor,backcolor,fullscreen",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        relative_urls : false,
        convert_urls : false,
        extended_valid_elements : 'object[width|height|classid|codebase|embed|param],param[name|value],embed[param|src|type|width|height|flashvars|wmode]',
        media_strict: false,
        inline_styles: true,
        remove_script_host : false,
        cleanup: true,
        forced_root_block : false,
        force_br_newlines : true,
        force_p_newlines : false
        //      file_browser_callback : "tinyBrowser",
    })
    
    $('.datepiker').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd"
    });
})