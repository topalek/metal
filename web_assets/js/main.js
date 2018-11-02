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
    var products = (localStorage.getItem('products')) ? JSON.parse(localStorage.getItem('products')) : [];
    var html = '<ul class="list-group">';
    $.each(products, (i, item) => {
        html = html + '<li class="list-group-item"> <span class="badge">&times;</span>' + item.title + '</li>';
    });
    html = html + '</ul>';
    $('.item-list').html(html);
}

var products = localStorage.getItem('products') ? JSON.parse(localStorage.getItem('products')) : {};
$(document).ready(function () {
    let date = new Date();
    console.log(products);
    $('.date').html((date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + '.' + (date.getMonth() + 1) + '.' + date.getFullYear());
    setInterval(getTime, 1000);

});