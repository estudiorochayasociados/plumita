function sendBuy(url,cod) {
    $.ajax({
        url: url + "/api/email/sendBuy.php",
        type: "POST",
        data: {cod: cod},
        beforeSend: function (data) {
            $('#textS').append("<span class='fa fa-spinner fa-spin fa-3x'></span><br>");
            $('#textS').append("<div class='text-uppercase text-center'>");
            $('#textS').append("<p class='fs-18 mt-10'>EXCELENTE, ESTAMOS ENVIANDO UN EMAIL CON TODA LA INFORMACIÓN DE TU PEDIDO.</p>");
            $('#textS').append("</div>");
            $('#modalS').modal('toggle');
        },
        success: function (data) {
            $('#textS').html('');
            $('#textS').append("<i class='fa fa-check-circle fs-80' style='color:green'></i><br>");
            $('#textS').append("<div class='text-uppercase text-center'>");
            $('#textS').append("<p class='fs-18 mt-10'>EMAIL ENVIADO EXITOSAMENTE.<BR/> MUCHAS GRACIAS.</p>");
            $('#textS').append("</div>");
            if ($('#modalS').hasClass('show')) {

            } else {
                $('#modalS').modal('toggle');
            }
        },
        error: function () {
            if ($('#modalS').hasClass('show')) {
                $('#modalS').modal('toggle');
            }
        }
    });
}