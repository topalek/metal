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
    let name;
    if (type == 0) {
        name = 'buy';
    } else {
        name = 'sale';
    }
    let products = getFromStorage(name);
    if (products) {
        var html = '<ul class="list-group">';
        $.each(products, (i, item) => {
            html = html + '<li class="list-group-item"> <span class="badge remove-item" data-id="' + item.id + '">&times;</span>' + item.title + ' (' + item.weight + ')' + '</li>';
        });
        html = html + '</ul>';
        $('.item-list').html(html);
    }

}

function writeToStorage(json, type) {
    let name;
    if (type == 0) {
        name = 'buy';
    } else {
        name = 'sale';
    }
    let products = getFromStorage(name);
    var product = {};
    json.forEach((item) => {
        product[item.name] = item.value;
    });
    products[product.id] = product;
    localStorage.setItem(name, JSON.stringify(products));


    return true;
}

function removeItem(id, type) {
    let name;
    if (type == 0) {
        name = 'buy';
    } else {
        name = 'sale';
    }
    let products = getFromStorage(name);
    delete products[id];
    localStorage.setItem(name, JSON.stringify(products));

}

$(document).ready(function () {
    let date = new Date();
    $('.date').html((date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + '.' + (date.getMonth() + 1) + '.' + date.getFullYear());
    setInterval(getTime, 1000);

});

function getFromStorage(name) {
    return (localStorage.getItem(name)) ? JSON.parse(localStorage.getItem(name)) : {};
}