function JBCountDown(settings) {
    var glob = settings;
    
    var left     = glob.endDate - glob.now;
    var passed   = glob.now - glob.startDate;
    var total    = left + passed;
    glob.seconds = 1;
    
    if (glob.endDate <= glob.now) {
        glob.seconds = left;
    }
    
    function deg(deg) {
        return (Math.PI/180)*deg - (Math.PI/180)*90
    }
    
    var clock = {
        set: {
            seconds: function(){
                
                var cdays = jQuery("#canvas_seconds").get(0);
                var ctx = cdays.getContext("2d");
                ctx.clearRect(0, 0, cdays.width, cdays.height);
                ctx.beginPath();
                ctx.strokeStyle = glob.secondsColor;
                
                ctx.shadowBlur    = 10;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 0;
                ctx.shadowColor = glob.secondsGlow;
                
                ctx.arc(133.1,133.7,124, deg(0), deg((360/total) *(passed + glob.seconds)));
                ctx.lineWidth = 17;
                ctx.stroke();
                
                jQuery(".clock .val").text(Math.round((left - glob.seconds) / 86400));
            }
        },
       
        start: function(){
            /* Seconds */
            var cdown = setInterval(function(){
                glob.seconds++;
                if (glob.seconds > left) {
                    clearInterval(cdown);
                    return;
                }
                clock.set.seconds();
            },1000);
        }
    }
    clock.set.seconds();
    clock.start();
}
var jnow;
var exCount = function(jnow) {
	JBCountDown({
		secondsColor : jacdsecondsColor,
		
		startDate   : jacdstartDate,
		endDate     : jacdendDate,
		now         : jnow
	});
};
setTimeout("exCount(jnow);", 2000);

jQuery(document).ready(function($){
	var timeurl = "http://www.timeapi.org/utc/now.json?callback=?";
	jnow=jacdnow;
    $.getJSON(timeurl, function (json){
        jnow = new Date(json.dateString).getTime()/1000;
    });
});