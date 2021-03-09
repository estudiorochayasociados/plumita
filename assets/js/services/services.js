function alertSide(message) {
    $.notify({
        icon: 'fa fa-times-circle',
        message: message,
    }, {
        element: 'body',
        type: "danger",
        placement: {
            from: "bottom",
            align: "right"
        },
        delay: 5000,
        timer: 1000,
        mouse_over: null,
        icon_type: 'class',
        template: '<div class="col-xs-10 col-md-6 pull-right">' +
            '<div data-notify="container" class=" alert alert-{0}" role="alert">' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>' +
            '</div>'
    });
}

function success(latest) {
    $.notify({
        // options
        icon: 'fa fa-shopping-cart',
        message: latest + ' fue agregado correctamente.',
    }, {
        // settings
        element: 'body',
        type: "success",
        placement: {
            from: "bottom",
            align: "right"
        },
        offset: 20,
        spacing: 10,
        z_index: 1031,
        delay: 5000,
        timer: 1000,
        icon_type: 'class',
        template: '<div class="col-xs-10 col-md-6 pull-right">' +
            '<div data-notify="container" class=" alert alert-{0}" role="alert">' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>' +
            '</div>'
    });
}

function CaptchaCallback(id, cod) {
    try {
        grecaptcha.render(id, {
            'sitekey': cod
        });
    } catch {
        grecaptcha.render(id, {
            'sitekey': cod
        });
    }
};


///Agregado 22/08/2019
function refreshFront() {
    var url = $('#cart-f').attr("data-url");
    $.ajax({
        url: url + "/api/atributes/refreshFront.php",
        type: "POST",
        data: $('#cart-f').serialize(),
        success: function(data) {
            data = JSON.parse(data);
            if (data['status'] == true) {
                $("#btn-a-1").prop("disabled", false);
                $("#amount").prop("disabled", false);
                $("#s-error").html('');
                $("#s-price").html('');
                $("#s-price").append("$ " + data['price']);
            } else {
                $("#btn-a-1").prop("disabled", true);
                $("#amount").prop("disabled", true);
                $("#s-price").html('');
                $("#s-error").html('');
                $("#s-error").append("<div class='alert alert-warning'>" + data['message'] + "</div>");

            }
        }
    });
}

// $("#provincia").change(function() {
//     $("#provincia option:selected").each(function() {
//         var url = $('#provincia').attr("data-url");
//         elegido = $(this).val();
//         $.ajax({
//             type: "GET",
//             url: url + "/assets/inc/localidades.inc.php",
//             data: "elegido=" + elegido,
//             dataType: "html",
//             success: function(data) {
//                 $('#localidad option').remove();
//                 var substr = data.split(';');
//                 for (var i = 0; i < substr.length; i++) {
//                     var value = substr[i];
//                     console.log(value);
//                     $("#localidad").append(
//                         $("<option></option>").attr("value", value).text(value)
//                     );
//                 }
//             }
//         });
//     });
// })

$("#provincia").change(function() {
    $("#provincia option:selected").each(function() {
        elegido = $(this).val();
        var url = $('#provincia').attr("data-url");
        $.ajax({
            type: "GET",
            url: url + "/assets/inc/localidades.inc.php",
            data: "elegido=" + elegido,
            dataType: "html",
            success: function(data) {
                $('#localidad option').remove();
                var substr = data.split(';');
                for (var i = 0; i < substr.length; i++) {
                    var value = substr[i];
                    // console.log(value);
                    $("#localidad").append(
                        $("<option></option>").attr("value", value).text(value)
                    );
                }
            }
        });
    });
})