jQuery(function(){
    jQuery.fn.timer = function(startTime){
        
         var inMinutes = 60;
        var inHours = inMinutes*60;
        var inDays = inHours * 24;
        var left = null;
         var days = (startTime / inDays);
        days = days - (days % 1);
        

        left = startTime - days * inDays;

        var hours = left / inHours;
        hours = hours - (hours % 1);
        
        left = left - hours * inHours;

        var minutes = left / inMinutes;
        minutes = minutes - (minutes % 1);

        var seconds = left - minutes * inMinutes;
        
        if(hours<10) hours = '0'+hours;
        if(minutes<10) minutes = '0'+minutes;
        if(seconds<10) seconds = '0'+seconds;
        $(this).html(days+" d "+hours+":"+minutes+":"+seconds);
}
});