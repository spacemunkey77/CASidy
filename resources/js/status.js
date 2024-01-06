window.$ = window.jQuery = require('jquery');

require('./bootstrap');

window.doorinfo = function(door, lock) {
	var doorText = door + "<br>" +
                   "Front door is " + lock;
    $(".doorinfo").html(doorText);
};

window.doorMode = function(operate) {

	lock_url = "/api/door";

	postData = {"lockmode": operate, "timestamp": $.now().toString()}

	console.log(postData);

	$.ajax({
        url: lock_url,
        method: "POST",
        data: JSON.stringify(postData),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function (xhr) {
    		xhr.setRequestHeader('Authorization', 'Bearer C2GCIlQImDO9Q2JsJU08LogX6MKPjnfbuVNNt5uNDa7icIC5Fg3pRTu9uqhJLxuiL1EhGIApQFuMgMS6');
		}
    }).done(function( data ) {
    	if (data.status) {
    		if (data.mode == "lock") {
    			$('img.frontdoor').attr('src', '/images/svg/doorlock.svg');
    		} else {
    			$('img.frontdoor').attr('src', '/images/svg/doorunlock.svg');
    		}
    	}
    });
};

window.getStatusUpdate = function() {
	
	var status_url = '/api/statuspage';

	$(".armtime").html("");
	$(".disarmtime").html("");
	$(".frontdoorlink").html("");
	$(".doorbelllink").html("");
	$(".boundarylink").html("");
	$(".alarmlink").html("");
	$(".alarmevent").html("");
	$(".battpercent").html("");
	$(".sunsettime").html("");
	$(".lightson").html("");
	$(".lightsoff").html("");
	$(".outdoorfahr").html("");
	$(".ambient").html("");
	$(".settemp").html("");
	$(".humidity").html("");

	$.ajax({
        url: status_url,
        method: "GET",
		beforeSend: function (xhr) {
    		xhr.setRequestHeader('Authorization', 'Bearer C2GCIlQImDO9Q2JsJU08LogX6MKPjnfbuVNNt5uNDa7icIC5Fg3pRTu9uqhJLxuiL1EhGIApQFuMgMS6');
		}
    }).done(function( data ) {
    	if (data.redir) {
    		location.href = data.redir;
    	};
    	$('.navbar-brand img').attr('src', '/images/' + data.brandicon);
    	$('img.frontdoor').attr('src', '/images/svg/' + data.dooricon);
    	$('img.alarmstatus').attr('src', '/images/svg/' + data.alarmarmed);
    	$('img.thermostat').attr('src', '/images/svg/' + data.thermostatico);
    	$(".armtime").html(data.armed);
    	$(".disarmtime").html(data.disarmed);
    	if (data.count.entries > 0) {
    		doorinfo(data.door, data.augustlock.doorBolt);	
    		$(".frontdoorlink").html('<a href="/status/door">Front Door Entry Log</a>');	
    	};
    	if (data.count.doorbell > 0) {
    		$(".doorbelllink").html('<a href="/status/doorbell">Doorbell Events</a>');
    	};
    	if (data.count.boundary > 0) {
    		$(".boundarylink").html('<a href="/status/activity">View Home/Away Log</a>');
    	};
    	if (data.count.alarms > 0) {
			$(".alarmlink").html('<a href="/status/sensors">Sensor Event Log</a>');
			$(".alarmevent").html('<span class="paddy-one">Last Event:</span> <span>' + data.lastalarmevent + '</span>');
    	};

    	$(".battpercent").html(data.augustlock.battery + " %");
    	$(".bar").css({"width": data.augustlock.battery + "%", "background-color": data.augustlock.color});
    	$(".sunsettime").html(data.sunset);
    	$(".lightson").html(data.lightson);
    	$(".lightsoff").html(data.homeaway);
    	$(".outdoorfahr").html(data.outdoorfahr);
    	$(".ambient").html(data.thermostat.ambient);
    	$(".settemp").html(data.thermostat.settemp);
    	$(".humidity").html(data.thermostat.humidity);
    });
};

$(document).ready(function(){
	getStatusUpdate();
	setInterval(function() {
    	getStatusUpdate();
   	}, 60 * 1000);
});
