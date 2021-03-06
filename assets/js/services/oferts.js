var start = 0;
var limit = 30;
var order = '';
const url = $("#grid-productsOfert").attr("data-url");

$(document).ready(() => {
    getData();
});


function orderBy(value) {
    order = value;
    getData();
}

function loadMore() {
    disableLoadMore();
    start += limit;
    getData('add');
}

function disableLoadMore() {
    $('#grid-productsOfert-btn').hide();
}

function enableLoadMore() {
    $('#grid-productsOfert-btn').show();
}

function getData(type) {
    let list = type != 'add' ? true : false;

    if (url) {
        if (list) {
            loader();
            start = 0;
            enableLoadMore();
        }
        $.ajax({
            url: url + "/api/products/listOfert.php?start=" + start + "&limit=" + limit + "&order=" + order,
            type: "POST",
            data: $('#filter-form').serialize(),
            success: (data) => {
                list ? reset() : enableLoadMore();
                $('#grid-productsOfert').append(data);
                //console.log($('#grid-productsOfert > div').length);
                if ($('#grid-productsOfert > div').length < limit) disableLoadMore();;
            }
        });
    }
}

function appendProducts(data) {
    if (Array.isArray(data)) {
        if (data.length < limit) {
            disableLoadMore();
        }

    } else {
        /*notFound();*/
    }
}

function loader() {
    reset();
    $('#grid-productsOfert').append("" +
        "<div class='col-xl-12 col-lg-12 col-md-12 col-sm-12'>" +
        "    <div class='product-wrap mb-10 mt-100 mb-400'>" +
        "        <div class='product-content text-center'>" +
        "            <i class='fa fa-circle-o-notch fa-spin fa-3x fs-70'></i>" +
        "        </div>" +
        "    </div>" +
        "</div>"
    );
}

function notFound() {
    reset();
    $('#grid-productsOfert').append("" +
        "<div class='col-xl-12 col-lg-12 col-md-12 col-sm-12'>" +
        "    <div class='product-wrap mb-35'>" +
        "        <div class='product-content text-center'>" +
        "            <i class='fa fa-times-circle fs-100' style='color: red'></i>" +
        "            <h4>No se encontró ningun producto con esas características.</h4>" +
        "        </div>" +
        "    </div>" +
        "</div>"
    );
    disableLoadMore();
}

function reset() {
    $('#grid-productsOfert').html('');
}