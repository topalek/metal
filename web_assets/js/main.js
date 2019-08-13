let isCalculated = false,
    clients = [1],
    maxClient = 1,
    clientNumber = 1;
const storageName = 'buy';

$('.operation-item,.sell-item').click(function (e) {
    e.preventDefault();
    let cartLink = $(this).attr('href'),
        clientId = $(this).data('client');
    console.log(clientId);
    if (clientId) {
        $.get(cartLink, function (result) {
            $('body').append('<div class="modals">' + result + '</div>');
        });
    }

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
    $('body').on('click', '.client', (e) => {
        let btn = $(e.target),
            products = btn.data('json'),
            clientId = btn.data('client');
        localStorage.setItem(storageName, JSON.stringify(products));
        setActiveBtn(clientId, products);
    });
    $('.new-client').on('click', e => {
        let btn = $(e.target);
        maxClient++;
        clients.push(maxClient);
        addBtn(maxClient, {});
        setActiveBtn(maxClient, {});
    })
});

function resetBtns() {
    let clientBtns = $('.client');
    clientBtns.removeClass('btn-info');
    clientBtns.addClass('btn-danger');
}

function addBtn(id, products) {
    $('#clients').append(getClientBnt(id, products));
}
function setActiveBtn(id, products) {
    let btn = $("#id-" + id);
    resetBtns();
    btn.removeClass('btn-danger');
    btn.addClass('btn-info');
    btn.data('json', products);
    setClientUrl(id);
}

function getFromStorage() {
    return (localStorage.getItem(storageName)) ? JSON.parse(localStorage.getItem(storageName)) : {};
}

function getClientBnt(id, json) {

    json = JSON.stringify(json);
    return `<button id="id-${id}" type="button" class="btn btn-danger client" data-json='${json}' data-client='${id}'>Клиент ${id}</button>`;
}

function setClientUrl(clientId) {
    let items = $('.operation-item');
    items.each((i, el) => {
        $(el).attr('href', $(el).attr('href').replace(/client=\d/, "client=" + clientId));
        $(el).data('client', clientId);
    })
}

function checkClients() {
    return $('.client').length !== 0;
}