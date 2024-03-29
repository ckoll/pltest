window.log = function f(){
    log.history = log.history || [];
    log.history.push(arguments);
    if(this.console) {
        var args = arguments, newarr;
        args.callee = args.callee.caller;
        newarr = [].slice.call(args);
        if (typeof console.log === 'object') log.apply.call(console.log, console, newarr); else console.log.apply(console, newarr);
    }
};
(function(a){
    function b(){}
    for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){
        a[d]=a[d]||b;
    }
})

(function(){
    try{
        console.log();
        return window.console;
    }catch(a){
        return (window.console={});
    }
}());

$.extend($.fn.disableTextSelect = function() {
    return this.each(function(){
        if($.browser.mozilla){//Firefox
            $(this).css('MozUserSelect','none');
        }else if($.browser.msie){//IE
            $(this).bind('selectstart',function(){
                return false;
            });
        }else{//Opera, etc.
            $(this).mousedown(function(){
                return false;
            });
        }
    });
})
    
/* scripts for each page */
    
    
$('.imgTrans').hover(function(){
    $(this).attr('src','/images/'+$(this).attr('data-nhover'))
}, function(){
    $(this).attr('src','/images/'+$(this).attr('data-hover'))
})
                
$('.hasmore').hover(function(){
    $(this).children('.dropdown').slideDown(100)
}, function(){
    $(this).children('.dropdown').slideUp(100)
})
                
$('.dropdown a').hover(function(){
    $(this).animate({
        paddingLeft: '+=20'
    },80)
},function(){
    $(this).animate({
        paddingLeft: '-=20'
    },80)
})


//Add dressup Like
$(function(){

    likeInit();

});




function likeInit()
{
    $('.likes, .like_button').unbind('click').click(function(){
        likeClickProcess(this);
    });
}

function likeClickProcess(el)
{
    var $this = $(el)
    $this.unbind('click');
    var id = $this.data('id')
    var mode = $this.data('mode')
    var type = $this.data('type');
    if (type == undefined) {
        type = 'add';
    }
    if(id){

        $.post('/'+mode+'/ajax',{
            'func': 'like_'+type,
            'id': id
        },function(data){



            var obj = $(data);

            if (obj.find('.signin_page').length) {
                location.href = '/signin';
            }

            data = $.parseJSON(data);

            if(data != null && data.err != undefined){
                alert(data.err)
            }else{
                var old = parseInt($this.text())
                if (type == 'add') {
                    $this.data('type', 'remove');
                    $this.removeClass('grey');
                    $this.html(old+1)
                } else {
                    $this.data('type', 'add');
                    $this.addClass('grey');
                    $this.html(old-1)
                }
            }

            $this.click(function(){
                likeClickProcess(this);
            });
        });
    }
}

$(document).ready(function(){
    $('.cool-button').mousedown(function(){
        $(this).addClass('mousedown');
    });

    $(document).mouseup(function(){
        $('.cool-button').removeClass('mousedown');
    });

    initAddToSet();




});


function initAddToSet() {
    $('.add-to-set button').unbind('click').click(function(){
        var cont = $(this).closest('.add-to-set');
        var photoId = cont.data('id');
        var setCont = cont.find('.set-dropdown-cont');
        if (setCont.is(':visible')) {
            setCont.hide()
        } else {
            loadsetCont(setCont);
            setCont.top(cont.top());
            setCont.left(cont.left());
        }
    });
}

function loadsetCont(setCont)
{
    setCont.load('/user/get_sets', function(){
        initSetCont(setCont);
        setCont.show();
    })
}


function initSetCont(cont)
{
    cont.find('.new-set-button').unbind('click').click(function(){
        var input = cont.find('.new-set-name');
        input.show();
        var button = cont.find('.save-new-set');
        button.show();

        button.unbind('click').click(function(){
            var name = input.val();
            if (name.replace(' ', '') == '') {
                alert('Set name can not be empty')
            } else {
                $.ajax({
                    url: '/user/add_set',
                    data: {name: name},
                    success: function(message){
                        loadsetCont(cont.closest('.set-dropdown-cont'));
                    }
                });
            }
        });

    });

    cont.find('.add-to-selected-set').unbind('click').click(function(){
        var setId = cont.find('.user-sets').val();
        $.ajax({
            url: '/user/add_to_set',
            data: {set_id: setId, photo_id: cont.closest('.add-to-set').data('id')},
            success: function(message){
                alert('Successfully added');
                cont.closest('.set-dropdown-cont').hide();
            }
        });
    });

}

function facebookPopup(url) {

    var url = "http://www.facebook.com/sharer/sharer.php?u="+url;
    var w = 550;
    var h = 450;
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    return window.open(url, "share", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

}

function twitterPopup(text, url) {
    var url = "http://twitter.com/share?text="+text+"&url="+url;
    var w = 550;
    var h = 450;
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    return window.open(url, "share", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}
