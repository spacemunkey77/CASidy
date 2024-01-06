window.$ = window.jQuery = require('jquery');

require('./bootstrap');

/** Set AJAX csrf-token headers **/

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
});

/** On document ready get states of homeaway and cloudy
    weather buttons **/

$( document ).ready( function() {

    $.get( "/options", function( result ) {
        if (result.options.rainy == true) {
            $('#rainyDay').prop('checked', true);
        }
        if (result.options.rainy == false) {
            $('#rainyDay').prop('checked', false);
        }
        if (result.options.homeaway == true) {
            $('#homeAway').prop('checked', true);
        }
        if (result.options.homeaway == false) {
            $('#homeAway').prop('checked', false);
        }  
        if (result.options.automation == true) {
            $('#homeAutomation').prop('checked', true);
        }
        if (result.options.automation == false) {
            $('#homeAutomation').prop('checked', false);
        }
        if (result.options.sunriselights == true) {
            $('#sunriseLights').prop('checked', true);
        }
        if (result.options.sunriselights == false) {
            $('#sunriseLights').prop('checked', false);
        }
    }, "json");

});

/** Set CloudyWeather Override **/

window.doRainyDay = function () {

    $.ajax({
        type : "POST",
        url : "/options",
        data: JSON.stringify({"action": "rainyday"}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result) {
            if (result.rainy == true) {
                $('#rainyDay').prop('checked', true);
            }
            if (result.rainy == false) {
                $('#rainyDay').prop('checked', false);
            }
        }
    });

};

/** Set homeAway **/

window.doHomeAway = function () {

    $.ajax({
        type : "POST",
        url : "/options",
        data: JSON.stringify({"action": "homeaway"}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result) {
            if (result.homeaway == true) {
                $('#homeAway').prop('checked', true);
            }
            if (result.homeaway == false) {
                $('#homeAway').prop('checked', false);
            }
        }
    });

}

/** Set homeAutomation **/

window.doHomeAutomation = function () {

    $.ajax({
        type : "POST",
        url : "/options",
        data: JSON.stringify({"action": "automation"}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result) {
            if (result.automation == true) {
                $('#homeAutomation').prop('checked', true);
            }
            if (result.automation == false) {
                $('#homeAutomation').prop('checked', false);
            }
        }
    });

}

/** Set SunriseLights **/

window.doSunriseLights = function () {

    $.ajax({
        type : "POST",
        url : "/options",
        data: JSON.stringify({"action": "sunriselights"}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result) {
            if (result.automation == true) {
                $('#sunriseLights').prop('checked', true);
            }
            if (result.automation == false) {
                $('#sunriseLights').prop('checked', false);
            }
        }
    });

}
