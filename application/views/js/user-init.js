$(function(){
    var user_id = $('input[name=user_id]').val()
    
    //Disconnect TW
    $('#tw_disconnect').click(function(){
        $this = $(this)
        $.post('/user/ajax', {
            'func': 'tw_disconnect'
        })
        $this.parent().prev().removeClass('connected')
        $this.parent().removeClass('green')
        $this.parent().html('<span class="status red">not connected</span>')
        return false;
    })
    
    
    //Disconnect FB
    $('#fb_disconnect').click(function(){
        $this = $(this)
        $.post('/user/ajax', {
            'func': 'fb_disconnect'
        }, function(data){
            if(data.redirect){
                window.location = data.redirect
            }
        }, 'json')
        $this.parent().prev().removeClass('connected')
        $this.parent().removeClass('green')
        $this.parent().html('<span class="status red">not connected</span>')
        return false;
    })
    
    $('.del_fav_brand').click(function(){
        var brand_id = $(this).data('id');
        $.post('/user/ajax',{
            'func':'del_fav_brand',
            'brand_id':brand_id
        },function(data){
            if(data.ok == 1) {
                $('#brand_'+brand_id).hide('medium');
                $('div.message').text('Brand deleted');
                $('div.message').show(30);
            }
        },'json')
    })
    
    $( "#fav_brands_modal" ).dialog({
        height: 400,
        width: 600,
        autoOpen: false,
        modal: true,
        resizable: false
    })
    $('a#open_edit_favorite_brands').click(function(){
        $('#fav_brands_modal').dialog("open");
    });
    
    //Notifications - modal
    $('#open_notif').click(function(){
        $( "#notif_modal" ).dialog( "open" );
        return false;
    })
    $( "#notif_modal" ).dialog({
        height: 240,
        width: 500,
        autoOpen: false,
        modal: true,
        resizable: false,
        buttons:
        [
        {
            text: "Cancel Changes",
            click: function() {
                $( this ).dialog( "close" );
            },
            'class': 'normal_button'
        },
        {
            text: "Update",
            click: function() {
                var all = {}
                $.each($('#notif_modal input'), function(i){
                    if($(this).is(':checked')){
                        all[$(this).attr('name')] = 1
                    }else{
                        all[$(this).attr('name')] = 0
                    }
                })
                $.post('/user/ajax', {
                    'func': 'user_notifications',
                    'notif': all
                })
                $('.message').hide()
                $('#content').prepend('<span class="message">User profile updated.</span>')
                $('div[class^=imgareaselect-]').hide();
                $( this ).dialog( "close" );
            }
        }
        ]
    })
    
    //Notification edit link
    $('input[name=notif]').change(function(){
        if($(this).val() == 1){
            $('#open_notif').show()
        }else{
            $('#open_notif').hide()
        }
    })
    $('.connected').live('click',function(){
        return false;
    })
    
    //Bloked users - modal
    $('#edit_blocked').click(function(){
        $( "#blocked_modal" ).dialog( "open" );
        return false;
    })
    $('#blocked_modal').dialog({
        resizable: false,
        autoOpen: false,
        modal: true,
        height: 360,
        width: 500,
        close: function(event, ui) {
            window.location = document.URL;
        }
    })
    $('.del_blocked_user').live('click',function(){
        var id = $(this).data('id')
        $.post('/user/ajax', {
            'func': 'del_blocked_user',
            'id': id
        },function(data){
            if(data == 0){
                $('#blocked_modal .modal').text('*no users blocked')
            }
        },'json')
        $(this).parent().parent().remove()
    })
    
    //Change Password
    $('#change_password').click(function(){
        $parent = $(this).parent()
        $parent.prev().prev().prev().prev().show();
        $parent.prev().prev().removeAttr('readonly')
        $parent.prev().prev().removeAttr('disabled')
        $parent.prev().prev().val('')
        $parent.next().show()
        $parent.remove()
    })
    
    //Change Avatar
    $('#change_avatar').click(function(){
        $('#change_avatar_button').trigger('click')
    })
    
    $('#change_avatar_button').change(function(){
        $('#loading').remove()
        $('#change_avatar_button').next().append('<img src="/images/loading.gif" id="loading">')
        $(this).parents('form').submit()
    })
    
    //ADD FRIEND
    $('#add_friend').live('click',function(){
        $this=$(this)
        var id = $this.data('id')
        $.post('/user/ajax', {
            'func': 'add_friend',
            'id': id
        },function(data){
            $this.val('request sent').attr('disabled','disabled')
        },'json')
    });
    $('#user_add_confirm').live('click',function(){
        $this=$(this)
        var id = $this.data('id')
        $.post('/user/ajax', {
            'func': 'confirm_friend',
            'id': id
        },function(){
            window.location = document.URL
        //            $this.attr('disabled','disabled');
        },'json')
    });
    $('.del_friend').live('click',function(){
        if(confirm('Delete friend?')){
            $this=$(this)
            var id = $this.data('id')
            $.post('/user/ajax', {
                'func': 'del_friend',
                'id': id
            },function(){
                window.location = document.URL
            },'json')
        }else{
            return false;
        }
    })
    
    $('#new_doll_name').click(function(){
        $('.dollname_form').show()
        $(this).prev().hide()
        $(this).hide()
    })
    
    $('input[name=save_dollname]').click(function(){
        var value = $(this).val()
        if(value == 'save'){
            var new_name = $('input[name=doll_name]').val()
            $.post('/user/ajax',{
                'func': 'change_dollname',
                'new_name': new_name
            })
            $('.dollname_form').hide()
            $('#new_doll_name').prev().text(new_name)
            $('#new_doll_name').prev().show()
            $('#new_doll_name').show()
        }else{
            $('.dollname_form').hide()
            $('#new_doll_name').prev().show()
            $('#new_doll_name').show()
        }
    })
    
    $('input[name=mark_sel]').click(function(){
        $(this).parents('form').submit()
    })
    
    $('.show-hide-button').click(function(){
        $next = $(this).next()
        if($next.is(':animated')) return false;
        
        if($next.is(':visible')){
            $next.slideUp()
            $(this).css('background-image','url("/images/max.png")')
        }else{
            $next.slideDown()
            $(this).css('background-image','url("/images/min.png")')
        }
    })
    
    //Wall
    $('textarea[name=wall_message]').focus(function(){
        if($(this).val() == $(this).data('default')){
            $(this).val('')
        }
    }).blur(function(){
        if($(this).val() == ''){
            $(this).val($(this).data('default'))
        }
    })
    
    $('input[name=wall_send]').click(function(){
        $elem = $('textarea[name=wall_message]')
        if($elem.val() == $elem.data('default')){
            $(this).parent().prepend('<div class="red">*Please enter a message.</div>')
            return false;
        }
    })
    
    function get_avaliable_days(date){
        if(user_id){
            $.post('/dressup/ajax',{
                'func': 'calendat_available_days',
                'date': date,
                'user': user_id
            }, function(data){
                if(data){
                    $.each($('.ui-state-default'), function(i,v){
                        if($.inArray($(this).text(), data) > -1){
                            $(this).parent().append('<div class="checked_day"><span></span></div>')
                        }
                    })
                }
            },'json')
        }
    }
    
    //dressup calendar
    $( ".calendar" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate: new Date(),
        onChangeMonthYear: function(year, month, inst) {
            get_avaliable_days(year+'-'+month+'-'+01)
        },
        onSelect: function(dateText, inst){
            $('#calendar_scrollbar').disableTextSelect()
            $('#calendar_scrollbar .overview').html('<img src="/images/loading_big.gif" class="loading_big">')
            get_avaliable_days(dateText)
            
            $.post('/dressup/ajax',{
                'func': 'dressup_items_date',
                'date': dateText,
                'uid': user_id
            },function(data){
                $('.loading_big').remove();
                if(data){
                    var scroll_width = parseInt(data.length) * 104;
                    $('#calendar_scrollbar .overview').css('width',scroll_width+'px');
                    $.each(data, function(i,v){
                        $('#calendar_scrollbar .overview').append('<div class="dressup_small"><a href="/'+v.username+'/dressup/'+v.id+'" class="dressup_small_img" style="background-image: url(/files/users/dressup/'+v.id+'.jpg);" ></a>'+v.date+'<br><a href="/'+v.username+'/dressup/'+v.id+'#comments">comments</a></div>')
                    })
                }else{
                    $('#calendar_scrollbar .overview').append('<em>*No dressups</em> - '+dateText);
                    $('#calendar_scrollbar .overview').css('width','330px');
                }
                $('#calendar_scrollbar').tinyscrollbar_update();
            },'json')
        }
    });
    
    $( "#full_calendar" ).datepicker({
        dateFormat: "yy-mm-dd",
        maxDate: new Date(),
        onChangeMonthYear: function(year, month, inst) {
            get_avaliable_days(year+'-'+month+'-'+01)
        },
        onSelect: function(dateText, inst){
            $('.calendar_data').html('<img src="/images/loading_big.gif" class="loading_big">')
            get_avaliable_days(dateText)
            
            $.post('/dressup/ajax',{
                'func': 'dressup_items_date',
                'date': dateText,
                'uid': user_id
            },function(data){
                $('.loading_big').remove();
                if(data){
                    $('.calendar_data').append('<strong class="center">Date: '+dateText+'</strong>')
                    $.each(data, function(i,v){
                        if(!v.comment){
                            v.comment = '';
                        }
                        $('.calendar_data').append('<div class="dressup_big"><a href="/'+v.username+'/dressup/'+v.id+'" class="dressup_small_img" style="background-image: url(/files/users/dressup/'+v.id+'.jpg);" ></a> <span>'+v.date+'</span><br>'+v.name+'<br>'+v.comment+'<br class="clear"></div>')
                    })
                }else{
                    $('.calendar_data').append('<p class="center"><em>*No dressups</em> - '+dateText+'</p>');
                }
            },'json')
        }
    });
    
    //load avaliable days
    var d = new Date();
    var month = d.getMonth() +1;
    var year = d.getFullYear();
    get_avaliable_days(year+'-'+month+'-01');
    
    

    
})