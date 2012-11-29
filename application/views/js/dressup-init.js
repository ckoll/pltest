$(function(){
    
    $(".numeric").numeric();
    
    $( "#dressup_menu" ).accordion({
        autoHeight: false,
        active: parseInt($('input[name=menu_active]').val())
    })
    
    $(function() {
        $( "#tabs" ).tabs();
    })
    
    //Item pages
    $('.page[data-page!=""]').click(function(){
        var page = $(this).data('page')
        var for_class = $(this).data('for')
        $('.'+for_class).hide()
        $('.'+for_class+'[data-block='+page+']').show()
        $('.page[data-for="'+for_class+'"]').removeClass('selected')
        $(this).addClass('selected')
    })
    
    //Delete Item
    $('.delete_item').click(function(){
        var id = $(this).data('id')
        var title = $(this).parent().prev().find('.item_name').html()
        var count = $(this).parent().prev().find('.item_count').html()
        var img = $(this).parent().prev().prev().attr('src')
        $('input[name=del_confirm]').attr('data-id',id)
        $( "#delete_item_modal" ).dialog("open")
        $('.del_info').html('<img src="'+img+'" height="100"><br>'+title+'<br>Current quantity: '+count+'<br><br>You will be delete 1 '+title+'<br>')
    })
    $('#delete_item_modal').dialog({
        width: 500,
        height: 340,
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) { 
            if($('#delete_item_modal').find('input[data-redirect]').length>=1){
                document.location = document.URL;
            }
        }
    })
    $('.close_del_modal').live('click',function(){
        $('#delete_item_modal').dialog('close')
    })
    $('input[name=del_confirm]').live('click',function(){
        var id = $(this).data('id')
        $.post('/dressup/ajax',{
            'func': 'del_item',
            'id': id
        },function(){
            $('#delete_item_modal .center').html('<strong>Item removed.</strong><br><br><input type="button" class="big_button close_del_modal" data-redirect="1" value="close">')
        })
    })
    
    //Add Auction Item
    $('.auction_item').click(function(){
        var id = $(this).data('id')
        var title = $(this).parent().prev().find('.item_name').html()
        var img = $(this).parent().prev().prev().attr('src')
        $('input[name=list_auction]').attr('data-id',id);
        $('.auction_info').html('<img src="'+img+'" height="100"><br>'+title+'<br><br>put 1 '+title+' for auction<br><br>');
        
        $('#add_auction_modal').dialog('open');
    });
    
    $('.hide_add_auction_modal').live('click',function(){ 
        $('#add_auction_modal').dialog('close')
    })
    $('#add_auction_modal').dialog({
        width: 550,
        height: 'auto',
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) { 
            if($('#add_auction_modal').find('input[data-redirect]').length>=1){
                document.location = document.URL
            }
            
        }
    })
    $('input[name=list_auction]').live('click',function(){
        var id = $(this).data('id');
        var start_price = $('input[name=start_price]').val();
        var reserve = $('input[name=reserve]').val();
        var price_type=$('input[name=price_type]:checked').val();
        var duration = $('select[name=duration]').val();
        
        if(start_price <= 0){
            alert('Start price must be greater than 0');
        }else{
            $.post('/dressup/ajax',{
                'func': 'add_auction',
                'id': id,
                'start_price': start_price,
                'reserve': reserve,
                'price_type':price_type,
                'duration':duration
            },function(data){
                if(data.err){
                    text = '<span class="err">'+(data.err)+'</span>';
                }else{
                    text = 'Item successfully added to auction'
                }
                $('#add_auction_modal .center').html(text+'<br><br><input type="button" class="big_button hide_add_auction_modal" data-redirect="1" value="close">')
                
            },'json')
        }
    })
    
    //Cancel auction item
    $('.cancel_auction').click(function(){
        var id = $(this).data('id')
        var title = $(this).parent().prev().find('.item_name').html()
        var img = $(this).parent().prev().prev().attr('src')
        $('input[name=rem_auction_confirm]').attr('data-id',id)
        $( "#cancel_auction_modal" ).dialog("open")
        $('.cancel_auction_info').html('<img src="'+img+'" height="100"><br>'+title+'<br>')
    })
    $('.close_auction_modal').live('click',function(){
        $('#cancel_auction_modal').dialog('close')
    })
    $('#cancel_auction_modal').dialog({
        width: 500,
        height: 340,
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) { 
            if($('#cancel_auction_modal').find('input[data-redirect]').length>=1){
                document.location = document.URL
            }
        }
    })
    $('input[name=rem_auction_confirm]').live('click',function(){
        var id = $(this).data('id')
        $.post('/dressup/ajax',{
            'func': 'rem_auction',
            'id': id
        },function(){
            $('#cancel_auction_modal .center').html('<strong>Item back in your closet.</strong><br><br><input type="button" class="big_button close_auction_modal" data-redirect="1" value="close">')
        })
    })
    
    //Cancel selling item
    $('.cancel_selling').click(function(){
        var id = $(this).data('id')
        var title = $(this).parent().prev().find('.item_name').html()
        var price = $(this).parent().find('.price').html()
        var img = $(this).parent().prev().prev().attr('src')
        $('input[name=rem_sell_confirm]').attr('data-id',id)
        $( "#cancel_selling_modal" ).dialog("open")
        $('.cancel_sell_info').html('<img src="'+img+'" height="100"><br>'+title+'<br>('+price+')<br>')
    })
    $('.close_sell_modal').live('click',function(){
        $('#cancel_selling_modal').dialog('close')
    })
    $('#cancel_selling_modal').dialog({
        width: 500,
        height: 340,
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) { 
            if($('#cancel_selling_modal').find('input[data-redirect]').length>=1){
                document.location = document.URL
            }
        }
    })
    $('input[name=rem_sell_confirm]').live('click',function(){
        var id = $(this).data('id')
        $.post('/dressup/ajax',{
            'func': 'rem_sell',
            'id': id
        },function(){
            $('#cancel_selling_modal .center').html('<strong>Item back in your closet.</strong><br><br><input type="button" class="big_button close_sell_modal" data-redirect="1" value="close">')
        })
    })
    
    //Sell Item
    $('.sell_item').click(function(){
        var id = $(this).data('id')
        var title = $(this).parent().prev().find('.item_name').html()
        var img = $(this).parent().prev().prev().attr('src')
        var count = $(this).parent().prev().find('.item_count').html()
        $.post('/dressup/ajax',{
            'func': 'item_average_price',
            'item': id
        },function(data){
            $('.item_one_price').text(data.price)
            $('.item_total_price').text(data.price)
        },'json')        
        $('input[name=sell1],input[name=sell2]').attr('data-id',id)
        $('.sell_info').html('<img src="'+img+'" height="100"><br>'+title+'<br><br>')
        $('.sell_count_now').html(count)
        $('select[name=sell_count]').empty()
        for (i=1; i<=count; i++){
            $('select[name=sell_count]').append('<option value="'+i+'">'+i+'</option>')
        }
        $( "#selling_modal" ).dialog("open")        
    })
    $('.hide_sell_modal').live('click',function(){ 
        $('#selling_modal').dialog('close')
    })
    $('#selling_modal').dialog({
        width: 700,
        height: 450,
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) { 
            if($('#selling_modal').find('input[data-redirect]').length>=1){
                document.location = document.URL
            }
            $('input[name=my_item_price]').val('')
        }
    })
    $('select[name=sell_count]').live('change',function(){
        var val = parseInt($(this).val())
        var item_one_price = parseInt($('.item_one_price').text())
        total = val * item_one_price;
        $('.item_total_price').text(total)
    })
    $('input[name=sell1]').click(function(){
        var id = $(this).data('id')
        var count = $('select[name=sell_count]').val()
        $.post('/dressup/ajax',{
            'func': 'sell_option1',
            'count': count,
            'id': id
        },function(data){
            if(data.err){
                text = '<span class="err">Sorry, you haven\'t '+count+' item(s)</span>'
            }else{
                text = 'Item(s) successfully sold out'
            }
            $('#selling_modal .center').html(text+'<br><br><input type="button" class="big_button hide_sell_modal" data-redirect="1" value="close">')
        },'json')
    })
    $('input[name=sell2]').live('click',function(){
        var id = $(this).data('id')
        var edit = $(this).data('edit')
        if(edit){
            selector = '#edit_selling_modal'
        }else{
            selector = '#selling_modal'
        }
        var count = $(selector+' select[name=sell_count]').val()
        var price = $(selector+' input[name=my_item_price]').val()
        if(price <= 0){
            alert('Price must be greater than 0');
        }else{
            $.post('/dressup/ajax',{
                'func': 'sell_option2',
                'count': count,
                'id': id,
                'price': price,
                'edit': edit
            },function(data){
                if(data.err){
                    text = '<span class="err">Sorry, you haven\'t '+count+' item(s)</span>'
                }
                if(edit){
                    if(!data.err){
                        text = 'Item(s) updated'
                    }
                    $('#edit_selling_modal .center').html(text+'<br><br><input type="button" class="big_button hide_edit_modal" data-redirect="1" value="close">')
                }else{
                    if(!data.err){
                        text = 'Item(s) added to your shop'
                    }
                    $('#selling_modal .center').html(text+'<br><br><input type="button" class="big_button hide_sell_modal" data-redirect="1" value="close">')
                }
            },'json')
        }
    })
    
    //Edit selling Item
    $('.edit_selling').live('click',function(){
        var id = $(this).data('id')
        var title = $(this).parent().prev().find('.item_name').html()
        var price = $(this).find('.price').text()
        var img = $(this).parent().prev().prev().attr('src')
        var count = $(this).parent().prev().find('.item_count').html()
        $.post('/dressup/ajax',{
            'func': 'item_edit_info',
            'item': id
        },function(data){
            $('.item_one_price').text(data.price)
            $('.item_total_price').text(data.price)
            $('.total_count').text(data.summ_element)
            $('select[name=sell_count]').empty()
            for (i=1; i<=data.count_enabled; i++){
                $('select[name=sell_count]').append('<option value="'+i+'">'+i+'</option>')
            }
            $('select[name=sell_count]').find('option[value="'+count+'"]').attr('selected','selected')
        },'json')
        $('input[name=sell1],input[name=sell2],.stop_selling').attr('data-id',id)
        $('.sell_info').html('<img src="'+img+'" height="100"><br>'+title+'<br><br>')
        $('.item_price').text(price)
        $('input[name=my_item_price]').val(parseInt(price))
        $('.sell_count_now').html(count)
        
        $( "#edit_selling_modal" ).dialog("open")        
    })
    $('.hide_edit_modal').live('click',function(){
        $('#edit_selling_modal').dialog('close')
    })
    $('#edit_selling_modal').dialog({
        width: 700,
        height: 450,
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) {
            if($('#edit_selling_modal').find('input[data-redirect]').length>=1){
                document.location = document.URL
            }
            $('input[name=my_item_price]').val('')
        }
    })
    $('.stop_selling').click(function(){
        var id = $(this).data('id')
        $('#edit_selling_modal').dialog('close')
        $('.cancel_selling[data-id='+id+']').trigger('click')
    })
    
    /*
     *  Dressup Page 
     */
    
    
    $('.load_items a').live('click',function(){
        
        $('#item_more').html('').css('opacity',0)
        
        $('.load_items a').removeClass('selected')
        $(this).addClass('selected')
        
        if(!$(this).is('.subcat')){
            $('.load_items p').slideUp()
            $('.load_items .slide').find('small').removeClass('opened')
        }
        if($(this).next().is('p')){
            if($(this).next('p').is(':visible')){
                $(this).next('p').slideUp()
            }else{
                $(this).next('p').slideDown()
                $(this).find('small').addClass('opened')
            }
        }
        if($(this).data('category')!=undefined){
            $('#dressup_items').html('<img src="/images/loading_big.gif" class="loading_big">')
            $.post('/dressup/ajax',{
                'func': 'load_items',
                'category': $(this).data('category')
            },function(data){
                $('.loading_big').remove()
                if(data){
                    $('#dressup_items').html(data)
                }else{
                    $('#dressup_items').html('<em>*no items<em>')
                }
            })
        }
    })
    
    function update_view(data){
        if(data.code){                  
            $.each(data.code, function(k,v){
                $('#doll_position').append('<div style="background-image: url(/files/'+v+')"></div>')
            })
            $.each(data.items, function(k,v){
                $this = v
                if($this.profileimage_dir == '[default]'){
                    $this.profileimage_dir = 'profilepics';
                }
                $('.overview').append('<div data-id="'+$this.id+'" class="used"><img src="/files/items/'+$this.directory+'/'+$this.profileimage_dir+'/'+$this.profileimage+'"><span class="remove_item hide"></span></div>')                  
            })
        }
        var t = setTimeout ( function(){
            $('.load_dressup').hide()
            clearTimeout(t)
        }, 1000 );
    }
    
    //get item
    $('.item_one').live('click',function(){
        $this = $(this)
        var id = $this.data('id')
        var type = $this.data('type')
        $('.load_dressup').show()
        
        //second click - remove item
        if($('.used[data-id="'+id+'"]').length > 0){
            $('.used[data-id="'+id+'"]').find('.remove_item').trigger('click')
            return false;
        }
        
        if(type == 'bg'){
            $('#item_more').html('')
            
            //change background
            $.post('/dressup/ajax',{
                'func': 'change_bg',
                'item': id
            },function(data){
                var t = setTimeout ( function(){
                    $('.load_dressup').hide()
                    clearTimeout(t)
                }, 1000 );          
                if(data){
                    $('#dressup').css('backgroundImage','url(/files/items/'+data.img+')')
                }
            },'json')
        }else if(type == 'hair' || type == 'haircolor'){
            if(type == 'hair'){
                $('#item_more').html('').css('opacity',1)
            }
            
            $.post('/dressup/ajax',{
                'func': 'change_hair',
                'item': id
            },function(data){
                if(data){
                    $('#doll_position').html('')
                    $('.used').remove()
                    update_view(data)
                    //show colors
                    if(data.colors && type!='haircolor'){
                        $.each(data.colors, function(k,v){
                            if(v.profileimage_dir == '[default]'){
                                v.profileimage_dir = 'files/items/'+v.directory+'/profilepics'
                            }
                            $('#item_more').append('<div class="item_one" data-id="'+v.id+'" data-type="haircolor"><img src="/'+v.profileimage_dir+'/'+v.profileimage+'"><small>'+v.item_name+'</small></div>')
                        })
                    }
                    
                }
            },'json')
            
        }else if(type == 'bodyparts'){
            $.post('/dressup/ajax',{
                'func': 'change_skin',
                'item': id
            },function(data){
                $('#doll_position').html('')
                $('.used').remove()
                update_view(data)
            },'json')
            
        }else if(type == 'face'){
        
            $.post('/dressup/ajax',{
                'func': 'change_face',
                'item': id
            },function(data){
                $('#doll_position').html('')
                $('.used').remove()
                update_view(data)
            },'json')
        
        } else{
            $('#item_more').html('')
            
            //add item
            $.post('/dressup/ajax',{
                'func': 'add_item',
                'item': id
            },function(data){
                if(data){
                    $('#doll_position').html('')
                    $('.used').remove()
                    update_view(data)
                    //change scroller width
                    if($('.used').length < 12){
                        width = 760;
                    }else{
                        width = $('.used').length * 64;
                    }
                    $('.used_items .overview').css('width',width+'px')
                    $('#scrollbar').tinyscrollbar_update();
                    
                }
            },'json')
            
            
        }
    })
    
    $('.used').live('mouseover',function(){
        $(this).find('.remove_item').show()
    })
    $('.used').live('mouseout',function(){
        $(this).find('.remove_item').hide()
    })
    //remove item
    //    $('.used').live('click',function(){
    //        $(this).find('.remove_item').trigger('click')
    //    })
    $('.remove_item').live('click',function(e){
        $('.load_dressup').show()
        $parent = $(this).parent()
        var id = $parent.data('id')
        
        $.post('/dressup/ajax', {
            'func': 'delete_item',
            'item': id
        }, function(data){
            $('#doll_position').html('')
            $('.used').remove()
            update_view(data)
            
            //change scroller width
            if($('.used').length < 12){
                width = 760;
            }else{
                width = $('.used').length * 64;
            }
            $('.used_items .overview').css('width',width+'px')
            $('#scrollbar').tinyscrollbar_update();
        },'json')
        $parent.remove()
        
    })
 
    //change doll layers
    $('input[name=arms_change_pos]').click(function(){
        $('.load_dressup').show()
        
        if($(this).val() == 'Arm Forward'){
            $(this).val('Arm Back')
            arms_layer = 'forward'
        }else{
            $(this).val('Arm Forward')
            arms_layer = 'back'
        }
        
        $.post('/dressup/ajax', {
            'func': 'change_arm_layer',
            'arms_layer': arms_layer
        }, function(data){
            $('#doll_position').html('')
            $('.used').remove()
            update_view(data)
        }, 'json')
    })
 
    //Use sortable
    $( ".used_items .overview" ).sortable({
        'items':'div',
        'scroll':false,
        placeholder: "used empty_used",
        update: function(ui,v){
            var items=[]
            $.each($('.used'), function(i){
                items[i] = $(this).data('id')
            })
            $.post('/dressup/ajax',{
                'func': 'sort_items',
                'items': items
            },function(data){
                $('#doll_position').html('')
                $('.used').remove()
                update_view(data)
            },'json')
        }
    });
    $( ".used_items .overview" ).disableSelection();
    $('#scrollbar').disableTextSelect();
    
    //Save look
    $('input[name=save_doll]').click(function(){
        $('#save_look_modal').dialog("open")
    })
    $('#save_look_modal').dialog({
        width: 500,
        height: 'auto',
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) { 
            $('.step1').show()
            $('.step2').hide()
            $('textarea[name=comment]').val('')
            $('select[name=saving] :first').attr('selected','selected').change()
            if($('input[name=look_url_redirect]')!="" && $('input[name=look_url_redirect]').val() != location.href && $('input[name=look_url_redirect]').val() != ''){
                document.location = $('input[name=look_url_redirect]').val()
            }
        }
    })
    $('input[name=close_save_modal]').live('click',function(){

        $('#save_look_modal').dialog('close')
    })
    $('input[name=save_look]').click(function(){
        var day_look = $('input[name=daylook]').val()
        
        $('#save_look_modal .step1').hide()
        $('#save_look_modal .modal').append('<img src="/images/loading_big.gif" class="center loading" style="margin: auto">')
        
        $.post('/dressup/ajax',{
            'func': 'save_look',
            'day_look': day_look,
            'outfit': 0,
            'comment': $('#save_look_modal textarea[name=comment]').val()
        },function(data){
            
            $('.loading').remove()
            $('#save_look_modal .step2').show()
            $('.result_dressup').css('background-image', 'url(/files/users/dressup/'+data.id+'.jpg)')
            $('.result_dressup').attr('href','/files/users/dressup-HD/'+data.id+'.jpg')
            var base_dressup_url = $('input[name=base_dressup_url]').val()
            $('input[name=look_url]').val(base_dressup_url+data.id)
            $('input[name=look_url_redirect]').val('/dressup/dress/'+data.id)
            $('input[name=dressup_id]').val(data.id)
            
        },'json')
    })
    $('input[name=look_url]').click(function(){
        $(this).select()
    })
    
    $('select[name=saving]').change(function(){
        if($(this).val() == 0){
            $('.day_look').show()
        }else{
            $('.day_look').hide()
        }
    })
    
    //Save outfit
    $('input[name=save_outfit]').click(function(){
        $('#outfit_modal').dialog("open")
    })
    $('#outfit_modal').dialog({
        width: 400,
        height: 'auto',
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) {
            $('.step_outfit1').show()
            $('.step_outfit2').hide()
            $('input[name=outfit_name]').val('')
        }
    })
    $('input[name=close_outfit_modal]').live('click',function(){
        $('#outfit_modal').dialog('close')
    })
    $('input[name=save_outfit_send]').click(function(){
        
        $('.step_outfit1').hide()
        $('#outfit_modal .modal').append('<img src="/images/loading_big.gif" class="center loading" style="margin: auto">')
            
        $.post('/dressup/ajax',{
            'func': 'save_look',
            'outfit': '1',
            'name': $('input[name=outfit_name]').val()
        },function(data){
            
            $('.loading').remove()
            $('.step_outfit2').show()
            $('.result_dressup').css('background-image','url(/files/users/dressup/'+data.id+'.jpg)')
            $('input[name=dressup_id]').val(data.id)
            
        },'json')
        
    })
    
    //outfits
    $('.outfit img.preview').click(function(){
        var src = $(this).attr('src')
        $('.outfit_preview').css('background-image', 'url('+src+')')
        var src_big = src.replace('dressup','dressup-HD')
        $('.outfit_preview').parent().attr('href',src_big)
        $('.outfit').removeClass('active')
        $(this).parent().addClass('active')
    })
    $('.wear_outfit, .wear_dressup').click(function(){
        $this = $(this)
        var id = $this.data('id')
        $this.parent().prev().trigger('click')
        $.post('/dressup/ajax',{
            'func': 'wear_outfit',
            'id': id
        },function(){
            $('.message').text('You\'r daily look changed')
            $('.message').clearQueue().fadeIn(300).delay(1500).fadeOut(300,function(){
                if($this.is('.wear_dressup')){
                    document.location = document.URL
                }
            })
        },'json')
    })
    $('#scrollbar_outfit').disableTextSelect();
    
    //Remove outfit
    $('.remove_outfit').click(function(){
        var id = $(this).data('id')
        $.post('/dressup/ajax',{
            'func': 'remove_outfit',
            'id': id
        })
        $(this).parent().remove()
    })
    
    //Remove dressup
    $('.remove_dressup').click(function(){
        var id = $(this).data('id')
        $.post('/dressup/ajax',{
            'func': 'remove_outfit',
            'id': id
        },function(){
            $('.message').text('You\'r dressup removed')
            $('.message').clearQueue().fadeIn(300).delay(1500).fadeOut(300,function(){
                document.location = '/dressup/edit_dressup'
            })
        })
    })
    
    //Duplicate Dressup
    $('.duplicate_dressup').click(function(){
        var id = $(this).data('id')
        $.post('/dressup/ajax',{
            'func': 'duplicate_dressup',
            'id': id
        },function(){
            $('.message').text('You\'r dressup duplicated')
            $('.message').clearQueue().fadeIn(300).delay(1500).fadeOut(300,function(){
                document.location = '/dressup/edit_dressup'
            })
        })
    })
    
    //Share dressup by email
    $('#share_email_modal').dialog({
        width: 400,
        height: 'auto',
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) {
            $('#share_email_modal .first').show()
            $('#share_email_modal .result').hide()
            $('textarea[name=share_emails]').val('')
            $('#share_email_modal .result').text('')
        }
    })
    $('.share_email').click(function(){
        $('#share_email_modal').dialog('open')
    })
    $('input[name=close_share_email]').click(function(){
        $('#share_email_modal').dialog('close')
    })
    $('input[name=share_email_run]').click(function(){
        var id = $('input[name=dressup_id]').val()
        var share_emails = $('textarea[name=share_emails]').val()
        var mode = $('.share_email').data('mode')
        if(!share_emails){
            alert('Please, enter email list for sharing')
        }else{
            $('#share_email_modal .result').html('<img src="/images/loading_big.gif" style="display: block; margin:auto">')
            $('#share_email_modal .first').hide()
            $('#share_email_modal .result').show()
            $.post('/dressup/ajax',{
                'func': 'share_email_dressup',
                'id': id,
                'share_emails': share_emails,
                'mode': mode
            },function(data){
                $('#share_email_modal .result').html('')
                if(data.err){
                    $.each(data.err, function(i,v){
                        $('#share_email_modal .result').append('<span class="red">'+v+'</span><br>')
                    })
                }
                if(data.ok){
                    $.each(data.ok, function(i,v){
                        $('#share_email_modal .result').append('<span class="green">'+v+'</span><br>')
                    })
                }else if(!data){
                    $('#share_email_modal .result').append('<span class="err">Server error, incorrect email list</span>')
                }
                $('#share_email_modal .result').append('<br><a href="'+document.URL+'" class="center">Close</a>')
            },'json')
        }
    })
    
    //Share dressup by FB
    $('#share_fb_modal').dialog({
        width: 400,
        height: 'auto',
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) {
            $('#share_fb_modal .first').show()
            $('#share_fb_modal .result').hide()
            $('#share_fb_modal .result').text('')
            $('textarea[name=share_fb_descr]').val('')
        }
    })
    $('.share_fb').click(function(){
        $('#share_fb_modal').dialog('open')
    })
    $('input[name=close_share_fb]').click(function(){
        $('#share_fb_modal').dialog('close')
    })    
    $('input[name=share_fb_run]').click(function(){
        var id = $('input[name=dressup_id]').val()
        var description = $('textarea[name=share_fb_descr]').val()
        var mode = $('.share_fb').data('mode')
        $('#share_fb_modal .first').hide()
        $('#share_fb_modal .result').show()
        $('#share_fb_modal .result').html('<img src="/images/loading_big.gif" style="display: block; margin:auto">')
        $.post('/dressup/ajax',{
            'func': 'send_share_fb',
            'id': id,
            'description': description,
            'mode': mode
        },function(data){
            if(data.err == 'not logined'){
                document.location = "/facebook"
            }else if(data.err){
                $('#share_fb_modal .result').html('<span class="err">'+data.err+'</span>')
            }else{
                $('#share_fb_modal .result').html('<span class="green">Dressup successfully shared in facebook</span>')
            }
            $('#share_email_modal .result').append('<a href="'+document.URL+'" class="center">Go to dressup page</a>')
        },'json')
    })
    
    //Send share twitter
    $('#share_tw_modal').dialog({
        width: 400,
        height: 'auto',
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) {
            $('#share_tw_modal .first').show()
            $('#share_tw_modal .result').hide()
            $('#share_tw_modal .result').text('')
            $('textarea[name=share_tw_descr]').val('')
        }
    })
    $('.share_tw').click(function(){
        $('#share_tw_modal').dialog('open')
    })
    $('input[name=close_share_tw]').click(function(){
        $('#share_tw_modal').dialog('close')
    })    
    $('input[name=share_tw_run]').click(function(){
        var id = $('input[name=dressup_id]').val()
        var description = $('textarea[name=share_tw_descr]').val()
        var mode = $('.share_tw').data('mode')
        $('#share_tw_modal .first').hide()
        $('#share_tw_modal .result').show()
        $('#share_tw_modal .result').html('<img src="/images/loading_big.gif" style="display: block; margin:auto">')
        $.post('/dressup/ajax',{
            'func': 'send_share_tw',
            'id': id,
            'description': description,
            'mode': mode
        },function(data){
            if(data.err == 'not logined'){
                document.location = "/twitter"
            }else if(data.err){
                $('#share_tw_modal .result').html('<span class="err">'+data.err+'</span>')
            }else{
                $('#share_tw_modal .result').html('<span class="green">Dressup successfully shared in twitter</span>')
            }
            $('#share_email_modal .result').append('<a href="'+document.URL+'" class="center">Go to dressup page</a>')
        },'json')
    })
    
    //Dressup pages
    $('.page_small').live('click',function(){
        $('.page_small').removeClass('active')
        $(this).addClass('active')
        $('.items_all_page').hide()
        $('.items_all_page[data-block='+$(this).data('page')+']').show()
    })
    
    
})