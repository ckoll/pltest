$(function(){
    
    $(".numeric").numeric();
    
    $("#maps").addClass("ui-accordion ui-accordion-icons ui-widget ui-helper-reset")
  .find("h3")
    .addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom")
    .hover(function() { $(this).toggleClass("ui-state-hover"); })
    .prepend('<span class="ui-icon ui-icon-triangle-1-e"></span>')
    .click(function() {
      $(this)
        .toggleClass("ui-accordion-header-active ui-state-active ui-state-default ui-corner-bottom")
        .find("> .ui-icon").toggleClass("ui-icon-triangle-1-e ui-icon-triangle-1-s").end()
        .next().toggleClass("ui-accordion-content-active").slideToggle();
      return true;
    })
    .next()
      .addClass("ui-accordion-content  ui-helper-reset ui-widget-content ui-corner-bottom")
      .show();

    
    //SEND FREE GIFTS
    
    $( "#free_gift_form input[value=Send]" ).click(function(e){
        e.preventDefault();
        var gift_id = $('input[name=free_gift]').val()
        var to_users = [];
        $.each($('#free_gift_form input[type=checkbox]:checked'), function(i){
            to_users[i] = $(this).val();
        });
        
        if(to_users.length>0){
        
            $.post('/explore/ajax', {
                'func': 'send_gifts',
                'gift_id' : gift_id,
                'to_users' : to_users
            }, function(data){
                $( "#content_sended" ).html('');
                $( "#content_no_sended" ).html('');
                if(data['sended']){
                    $( "#content_sended" ).append('<h2>Success! Your gifts have been sent to '+(data['sended'].length)+' friend(s)</h2>');
                    for(var i=0; i<data['sended'].length; i++){
                        $( "#content_sended" ).append('<div class="avatar100 single_users" style="height: 130px; margin-right: 20px"><div class="border"></div><img src="'+data['sended'][i]['avatar']+'"><br><span class="single-name">'+data['sended'][i]['username']+'</span></div>');
                    }
                }else if(data['no_sended']){
                    $( "#content_sended" ).append('<h2>sorry You alredy sending gifts for users:</h2>');
                    for(var i=0; i<data['no_sended'].length; i++){
                        $( "#content_sended" ).append('<div class="avatar100 single_users" style="height: 130px; margin-right: 20px"><div class="border"></div><img src="'+data['no_sended'][i]['avatar']+'"><br><span class="single-name">'+data['no_sended'][i]['username']+'</span></div>');
                    }
                }                
                $( "#gifts_sended_modal" ).dialog( "open" );
            }, 'json')
        
        }
        
    })
    
    $( "#gifts_sended_modal" ).dialog({
        height: 600,
        width: 800,
        autoOpen: false,
        modal: true,
        resizable: false,
        buttons: {
            Close: function() {
                $( this ).dialog( "close" );
                document.location='/';
            }
        }
    })
    
    
    
    $('.ui-icon-closethick').click(function(){
        if($(this).prev().text() == 'Sending Gifts'){
            document.location='/';
        }
    })

    //Email Login modal window
    $('.login_open').click(function(){
        $('#system').val($(this).data('system'))
        $('#ui-dialog-title-login_modal').text('Login into '+$(this).text())
        $('.system_name').text($(this).data('system'))
        $( "#login_modal" ).dialog( "open" );
        return false;
    })
    $( "#login_modal" ).dialog({
        height: 240,
        width: 500,
        autoOpen: false,
        modal: true,
        resizable: false
    })
    
    //Check Login params
    $('#login_me').click(function(){
        if(!$('input[name=u_login]').val()){
            $('.err').text('Please, insert your login')
            $('.err').fadeIn()
            return false;
        }else if(!$('input[name=u_password]').val()){
            $('.err').text('Please, insert your password')
            $('.err').fadeIn()
            return false;
        }
        $('.err').fadeOut()
        return true;
    })
    
    $('#show_invite_form').click(function(){
        $('.invite_form').show()
        $('.invite_form .hide').show()
        return false;
    })  
    
    //Invide email textarea
    $('#invite_email').focus(function(){
        if($(this).data('default') == $(this).val()){
            $(this).val('')
        }
    }).blur(function(){
        if( $(this).val() == ''){
            $(this).val($(this).data('default'))
        }
    })
    
    $('input[name=send_invite]').click(function(){
        $('.err').hide()
        var system = $('input[name=system]').val()
        var emails = $('textarea').val()
        var email_def = $('textarea').data('default')
        if(!system && emails==email_def){
            $('.err').text('Please, enter friends email for inviting').show()
            return false;
        }
        return true;
    })
    
    //sort friends
    $('select[name=sort_friends]').change(function(){
        $.post('/explore/ajax',{
            'func': 'sort_friends',
            'val': $(this).val()
        },function(){
            document.location = document.URL
        })
    })
    
    
    /* Market */
    
    $('.market_filter').click(function(){
        $.post('/explore/ajax',{
            'func': 'market_filter',
            'type': $(this).data('type')
        },function(dataurl){
            document.location = dataurl
        },'json')
    })
    
    $('select[name=market_sort]').change(function(){
        $.post('/explore/ajax',{
            'func': 'market_sort',
            'type': $(this).val()
        },function(){
            document.location = document.URL
        },'json')
    })
    
    $('.buy_item').click(function(){
        var shop_type = $(this).data('type')
        $.post('/explore/ajax',{
            'func': 'check_buy_item',
            'item_id': $(this).data('id'),
            'shop_type': shop_type
        },function(result){
            if(result.err){
                if(result.errtype == 'jewels'){
                    $('.jewels_info').show()
                    $('#buy_modal .error').show().find('p.info').html(result.err)
                }else if(result.errtype == 'buttons'){
                    $('.buttons_info').show()
                    $('#buy_modal .error').show().find('p.info').html(result.err)
                }else{
                    $('.syserr_info').show()
                    $('#buy_modal .close_modal').attr("data-redirect",'1')
                }                
                $('#buy_modal .ok').hide()
            }else{
                $('#buy_modal .ok').show()
                $('#buy_modal .error').hide()
                $('#item_data').html('<img height="90" src="/files/'+result.data.preview+'"><br>'+result.data.title+'<br>'+result.data.price+'<input type="hidden" name="item_hide_id" value="'+result.data.id+'">')
            }
            $( "#buy_modal" ).dialog( "open" );
        },'json')
        return true;
    })
    
    $('input[name=buy_button]').live('click',function(){
        var id = $('input[name=item_hide_id]').val()
        var shop_type = $(this).data('type')
        $.post('/explore/ajax',{
            'func': 'buy_item',
            'item_id': id,
            'shop_type': shop_type
        },function(rez){
            if(rez.err){
                $('.ok').text('').hide()
                $('.syserr_info').show()
            }else{
                $('.ok').html('<strong>Item purchased.</strong><br><br><a href="/dressup">My Inventory</a><br><input type="button" class="big_button close_modal" data-redirect="1" value="close">')
            }
        },'json')
    })
    
    $('#open_bid_history').live('click',function(){
       
        $( "#bid_history_modal" ).dialog( "open" );
    });
    $('.bid_now').click(function(){
       var bid_nowTHIS = $(this);
       $.post('/explore/ajax',{
            'func': 'get_userauction_item',
            'item_id' : $(this).attr('data-id'),
            'auction_type':$(this).data('type')
        },function(result){
            $('#item_data').html('<img height="90" src="/files/items/'+result.preview+'"><br><p class="center" style="width:90px">'+result.title+'</p>')
            $('#cur_bid').text(result.current_bid);
            $('#count_bids').text(result.count_bids);
            $('.duration').text(result.duration);
            $('#reserve').text(result.reserve);
            
            if(result.reserve <= result.current_bid){
               $('#reserve_met').text('met');
            }else{
                $('#reserve_met').text('not met');
            }
            
            $('')
            if(result.time_left<1)
                $('#time_left').html('0');
            else
                $('#time_left').timer(result.time_left);
            $('input[class~=place_bid]').attr('data-item-id',bid_nowTHIS.attr('data-id'));
            
            
            $.each(result.bid_history, function(i,val){
                    $('#bid_history_table').prepend('<tr><td>'+val.username+'</td><td>'+val.price+'</td><td>'+i+'</td></tr>');
            });
            
            var increment=null;
            if(result.current_bid >= 1000)
                increment = 10;
            else if(result.current_bid < 1000 && result.current_bid >= 100)
                increment = 5;
            else
                increment = 1;
            
            $('input[name=bid]').val((parseInt(result.current_bid)+parseInt(increment)));
            $( "#bid_modal" ).dialog( "open" );
        },'json')
        return true;
    });
    
    $('input[name=place_bid]').live('click', function(){
        var id = $(this).attr('data-item-id');
        var auction_type = $(this).data('type');
        $.post('/explore/ajax',{
            'func': 'bid_item',
            'item_id': id,
            'auction_type': auction_type,
            'bid':$('input[name=bid]').val()
        },function(rez){
            if(rez.err){
               alert(rez.err);
            }else{
                $('.ok').html('<strong>You bid ok.</strong><br><br><input type="button" class="big_button close_modal" data-redirect="1" value="close">')
            }
        },'json')
    });
    
    $('.close_modal').live('click',function(){
        var modal_id = $(this).parents('.modal').parent().attr('id');
        
        $( "#"+modal_id ).dialog("close");
        
    })    
    
    $( "#buy_modal" ).dialog({
        width: 500,
        height: 300,
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) { 
            if($('#buy_modal').find('input[data-redirect]').length>=1){
                document.location = document.URL
            }
            $('.jewels_info').hide()
            $('.buttons_info').hide()
            $('.syserr_info').hide()
            $('.error').hide()
            $('.ok').hide()
            $('.item_data').html('')
            $('#buy_modal .error').find('p.info').html('')
        }
    })
    $( "#bid_history_modal" ).dialog({
        width: 300,
        height: 'auto',
        autoOpen: false,
        modal: true,
        resizable: false
    })
    
    $( "#bid_modal" ).dialog({
        width: 500,
        height: 300,
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) { 
            if($('#bid_modal').find('input[data-redirect]').length>=1){
                document.location = document.URL
            }
            $('#item_data').html('')
            $('#cur_bid').text('');
            $('#count_bids').text('');
            $('.duration').text('');
            $('#reserve').text('');
            $('#time_left').text('');
            $('input[name=bid]').val('');
            $('#bid_history_table').html('');
        }
    })
    
    //bank (exchange buttons or jewels)
    $('input[name=bank_exchange]').click(function(){
        var item=null;
        var cout=null;
        if( ($(this).attr('data-item') ) == 'buttons' ){
            item='buttons';
            cout=$('input[name=ex_buttons_count]').val();
            
        }else if( ($(this).attr('data-item') ) == 'jewels' ){
            item='jewels';
            cout=$('input[name=ex_jewels_count]').val();
            
        }
        
        if(item!=null && cout!=null){
            $.post('/explore/ajax',{
                'func':'bank_exchange',
                'item':item,
                'count': cout
            },function(data){
                if(data.err){
                    if(item == 'buttons'){
                        $('.buttons_err').show()
                    }else{
                        $('.jewels_err').show()
                    }
                }else{
                    if(item == 'buttons' && cout < 1000){
                        $('.buttons_less_err').show()
                    }else if(item == 'jewels' && cout < 1){ 
                        $('.jewels_less_err').show()
                    }else{
                        $('.exchange_done').show()
                    }
                }
                $('#exchange_modal').dialog('open')
            }, 'json')
        }
    })
    $('#exchange_modal').dialog({
        width: 500,
        height: 340,
        autoOpen: false,
        modal: true,
        resizable: false,
        beforeClose: function(event, ui) { 
            document.location = document.URL;
            $('#item_data').html('')
            $('#cur_bid').text('');
            $('#count_bids').text('');
            $('.duration').text('');
            $('#reserve').text('');
            $('#time_left').text('');
            $('input[name=bid]').val('');
        }
    })
    $('.close_exchange_modal').live('click',function(){
        $('#exchange_modal').dialog('close')
    })
    
    $('.buy_jewels_content input[name=PayPal]').click(function(e){
        
        var j_count = $('.buy_jewels_content select[name=jewels_count] option:selected').val();
        if(j_count==0){e.preventDefault();alert('Please select the count of diamonds you want to buy.');}
        
    });
    function prID(){
        var j_count = $('.buy_jewels_content select[name=jewels_count] option:selected').val();
        if(j_count!=0){
        $.post('/explore/ajax',{
                'func': 'get_checkout_prID',
                'j_count': j_count
            },function(rez){
                $('.buy_jewels_content input[name=product_id]').val(rez.prID);
            },'json');
        }
    }
    $('.buy_jewels_content select[name=jewels_count]').change(function(){
        prID();
    });
    
    $('.buy_jewels_content select[name=jewels_count]').blur(function(){
        prID();
    });
    
    $('.buy_jewels_content input[name=2checkout]').click(function(e){
        var j_count = $('.buy_jewels_content select[name=jewels_count] option:selected').val();
        if(j_count==0){e.preventDefault();alert('Please select the count of diamonds you want to buy.');}
    });
    
    $('select[name=selectAnotherBrand]').change(function(){
        document.location = '/brands/'+($(this).val());
    });
    
    $('.photo_tumb').hover(function(){
        $(this).children('.likes, .comments, .delete, .edit').fadeIn()
    },function(){
        $(this).children('.likes, .comments, .delete, .edit').fadeOut()
    })
    
    $('.del_photo').click(function(){
        var id = $(this).data('id')
        $.post('/upload/ajax',{
            'func': 'del_uploaded_img',
            'id': id
        })
        $(this).parent().remove()
    })
    
})
