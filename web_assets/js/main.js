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
    var products = (localStorage.getItem('products')) ? JSON.parse(localStorage.getItem('products')) : {};
    var html = '<ul class="list-group">';
    $.each(products, (i, item) => {
        html = html + '<li class="list-group-item"> <span class="badge">&times;</span>' + item.title + '</li>';
    });
    html = html + '</ul>';
    $('.item-list').html(html);
}

function writeToStorage(json) {
    var products = (localStorage.getItem('products')) ? JSON.parse(localStorage.getItem('products')) : {};
    json.forEach((item) => {
        product[item.name] = item.value;
    });
    products[product.id] = product;
    localStorage.setItem('products', JSON.stringify(products));
    return true;
}

$(document).ready(function () {
    let date = new Date();
    $('.date').html((date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + '.' + (date.getMonth() + 1) + '.' + date.getFullYear());
    setInterval(getTime, 1000);

});