let isCalculated = false;
const storageName = 'buy';

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

function buildItemList() {

    let products = getFromStorage();
    if (products) {
        var html = '<ul class="list-group">';
        $.each(products, (i, item) => {
            html = html + '<li class="list-group-item"> <span class="badge remove-item" data-id="' + i + '">&times;</span>' + item.title + ' (' + item.weight + 'x' + item.sale_price + ') - ' + item.dirt + '% = ' + item.total + '</li>';
        });
        html = html + '</ul>';
        $('.item-list').html(html);
    }

}

function writeToStorage(json) {
    let products = getFromStorage();
    var product = {};
    json.forEach((item) => {
        product[item.name] = item.value;
    });
    let key = Object.keys(products).length;
    products[key++] = product;
    localStorage.setItem(storageName, JSON.stringify(products));
    return true;
}

function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}

function removeItem(id) {

    let products = getFromStorage(storageName);
    delete products[id];
    localStorage.setItem(storageName, JSON.stringify(products));

}

$(document).ready(function () {
    setInterval(getTime, 1000);
});

function getFromStorage() {
    return (localStorage.getItem(storageName)) ? JSON.parse(localStorage.getItem(storageName)) : {};
}
