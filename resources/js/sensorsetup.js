window.$ = window.jQuery = require('jquery');

require('./bootstrap');

/** Set AJAX csrf-token headers **/

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
});

/** On document ready get states of away and cloudy
    weather buttons **/

$( document ).ready( function() {

    $.get( "/sensors/options", function( result ) {
        if (result.options.active == true) {
            $('#homeMode').prop('checked', true);
        }
        if (result.options.active == false) {
            $('#homeMode').prop('checked', false);
        }
        if (result.options.away == true) {
            $('#away').prop('checked', true);
        }
        if (result.options.away == false) {
            $('#away').prop('checked', false);
        }
        if (result.options.testing == true) {
            $('#testing').prop('checked', true);
        }
        if (result.options.testing == false) {
            $('#testing').prop('checked', false);
        }
        if (result.options.nightsafety == true) {
            $('#nightsafety').prop('checked', true);
        }
        if (result.options.nightsafety == false) {
            $('#nightsafety').prop('checked', false);
        }
    }, "json");

});

/** (Un)Set Alarm to Armed Mode. **/

window.doHomeMode = function () {

    $.ajax({
        type : "POST",
        url : "/sensors/options",
        data: JSON.stringify({"action": "activatesensor"}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result) {
            if (result.active == true) {
                $('#homeMode').prop('checked', true);
                let alarmedLogo = "/images/alarmarmed.png";
                $('.navbar-brand img').attr('src', alarmedLogo);
            }
            if (result.active == false) {
                $('#homeMode').prop('checked', false);
                let normalLogo = "/images/houseicon.png";
                $('.navbar-brand img').attr('src', normalLogo);
            }
        }
    });

};

/** (Un)Set Alarm to Away **/

window.doAway = function () {

    $.ajax({
        type : "POST",
        url : "/sensors/options",
        data: JSON.stringify({"action": "away"}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result) {
            if (result.away == true) {
                $('#away').prop('checked', true);
                let alarmedLogo = "/images/alarmarmed.png";
                $('.navbar-brand img').attr('src', alarmedLogo);
            }
            if (result.away == false) {
                $('#away').prop('checked', false);
                let normalLogo = "/images/houseicon.png";
                $('.navbar-brand img').attr('src', normalLogo);
            }
        }
    });

};

/** (Un)Set Testing Mode **/

window.doTesting = function () {

    $.ajax({
        type : "POST",
        url : "/sensors/options",
        data: JSON.stringify({"action": "testing"}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result) {
            if (result.testing == true) {
                $('#testing').prop('checked', true);
            }
            if (result.testing == false) {
                $('#testing').prop('checked', false);
            }
        }
    });

};

/* (Un)Set NightTime Safety Mode */

window.doNightSafety = function () {

    $.ajax({
        type : "POST",
        url : "/sensors/options",
        data: JSON.stringify({"action": "nightsafety"}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result) {
            if (result.testing == true) {
                $('#nightsafety').prop('checked', true);
            }
            if (result.testing == false) {
                $('#nightsafety').prop('checked', false);
            }
        }
    });

};

window.doProvision = function () {

    $.ajax({
        type : "POST",
        url : "/sensors/setup",
        data: JSON.stringify({"action": "provision"}),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result) {
            if (result.provision == true) {
                $('#provision').prop('disabled', true);
            }
            if (result.provision == false) {
                $('#provision').removeClass('btn-primary');
                $('#provision').addClass('btn-danger');
            }
        }
    });

};
