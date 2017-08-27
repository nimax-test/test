// инициация
$(function () {
    // загрузка из кэша
    $("#local").click(function (event) {
        event.preventDefault();
        ajaxUpdate("#info", "rates", checkCodes());
    });
    // загрузка из источника
    $("#remote").click(function (event) {
        event.preventDefault();
        ajaxUpdate("#info", "rates/update", checkCodes());
    });
    // начальные данные
    $('#remote').click();
    // обновление по таймеру каждые 24 часа
    setInterval("$('#remote').click();", 86400000);
});

// список валют
function checkCodes() {
    var codes = []; // массив отмеченных кодов валют для загрузки
    $("input:checked").parent().each(function (index, element) {
        code = $(element).text().replace(/\s/g, '');
        codes.push(code); // отмеченные
    });
    return {codes: codes};
}

// ajax обновление элемента на странице
function ajaxUpdate(selector, url, data, async, type) {
    $("body").append("<div id='ajax'></div>");
    $.ajax({
        url: url,
        data: data || {},
        type: type || "POST",
        async: async === undefined ? true : async,
        success: function (html) {
            $(selector).html(html);
        },
        complete: function () {
            setTimeout(function () {
                $("#ajax").remove();
            }, 500);
        }
    });
}