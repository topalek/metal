$('.operation-item').click(function (e) {
    e.preventDefault();
    showCart();
});

function showCart() {
    let cartLink = $('.operation-item').attr('href');
    $.get(cartLink, function (result) {
        $('body').append('<div class="modals">' + result + '</div>');
    });
}

function getTime() {
    let date = new Date();
    let sec = date.getSeconds();
    if (sec < 10) {
        sec = "0" + sec;
    }
    let timeStr = date.getHours() + ":" + date.getMinutes() + ":" + sec;
    $('.time').html(timeStr);

}

$(document).ready(function () {
    let date = new Date();
    $('.date').html((date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + '.' + (date.getMonth() + 1) + '.' + date.getFullYear());
    setInterval(getTime, 1000);

});