$().ready(function() {
    $('textarea.tinymce').tinymce({
        // Location of TinyMCE script
        script_url : '/js/tiny_mce/tiny_mce.js',

        // General options
        theme : "advanced",
        plugins: "emotions",
        theme_advanced_buttons1 : "mybutton,bold,italic,underline,separator,emotions",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        cleanup: true,
        forced_root_block : false,
        force_br_newlines : true,
        force_p_newlines : false,
    });
    
});