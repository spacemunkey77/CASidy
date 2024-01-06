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

    $("div.lightSquare").click(function(event) {

        var room = $(this).data("room");
        var clickButton = $(this).attr("id");

        $.ajax({
            type : "POST",
            url : "/lights",
            data: JSON.stringify({"action": "room", "room": room}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result) {
                if (result.status == "success") {
                    $("#" + clickButton).fadeOut(200).fadeIn(200);
                }
            }
        });
    });

    $("div.outletSquare").click(function(event) {

        var outlet = $(this).data("outlet");
        var clickButton = $(this).attr("id");

        $.ajax({
            type : "POST",
            url : "/outlets",
            data: JSON.stringify({"action": "outlet", "outlet": outlet}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result) {
                if (result.status == "success") {
                    $("#" + clickButton).fadeOut(200).fadeIn(200);
                }
            }
        });
    });
});
