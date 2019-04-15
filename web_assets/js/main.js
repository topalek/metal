$('.operation-item').click(function (e) {
    e.preventDefault();
    let cartLink = $(this).attr('href');
    $.get(cartLink, function (result) {
        $('body').append('<div class="modals">' + result + '</div>');
    });
});


function getTime() {
    let date = new Date();
    let sec = date.getSeconds();
    if (sec < 10) {
        sec = "0" + sec;
    }
    let timeStr = date.getHours() + ":" + date.getMinutes() + ":" + sec;
    $('.time').html(timeStr);

}

function buildItemList(type) {

    let products = getFromStorage(type);
    if (products) {
        var html = '<ul class="list-group">';
        $.each(products, (i, item) => {
            html = html + '<li class="list-group-item"> <span class="badge remove-item" data-id="' + item.id + '">&times;</span>' + item.title + ' (' + item.weight + 'x' + item.sale_price + ') - ' + item.dirt + '% = ' + item.total + '</li>';
        });
        html = html + '</ul>';
        $('.item-list').html(html);
    }

}

function writeToStorage(json, type) {
    let products = getFromStorage(type);
    var product = {};
    json.forEach((item) => {
        product[item.name] = item.value;
    });
    products[product.id] = product;
    localStorage.setItem(getStorageName(type), JSON.stringify(products));


    return true;
}

function removeItem(id, type) {

    let products = getFromStorage(type);
    delete products[id];
    localStorage.setItem(getStorageName(type), JSON.stringify(products));

}

$(document).ready(function () {
    setInterval(getTime, 1000);
});

function getFromStorage(type) {
    return (localStorage.getItem(getStorageName(type))) ? JSON.parse(localStorage.getItem(getStorageName(type))) : {};
}

function getStorageName(type) {
    return (type == 0) ? 'buy' : 'sale';
}