var projectsContainerId = '#posts'; // контейнер, в который добалять вновь загруженные данные
var distanceFromBottomToStartLoad = 500; // в пикселях -- за сколько пикселей до конца страницы начинать загрузку
var $loadManager;

/* Элемент руководящей загрузкой - в его полях содержим все опции необходимые
  для выборки очередной порции данных или прекращения загрузки */
var loaderManagerElementId = '#loader-manager'; // элемент, руководящий загрузкой

$(document).ready(function () {
    $loadManager = $(loaderManagerElementId);
    loadData();
    initScrollingLoad(); // инициаллизируем обработчик прокрутки и фоновую загрузку
});


var loadAjax = false; // индикатор выполняения запроса подгрузки ленты

function initScrollingLoad() {


    $(window).scroll(function () {

        // Проверяем пользователя, находится ли он в нижней части страницы

        if (($(window).scrollTop() + $(window).height() > $(document).height() - distanceFromBottomToStartLoad) && !loadAjax) {


            console.log('infinit load event!!');
            loadData();

        }
    });
}

function loadData() {
    loadAjax = true;
    setTimeout(function () {

        var url = $loadManager.data('url') + '?last=' + $loadManager.data('last');

       // $loadManager.data('offset', offset + limit); // сразу выставляем новое смещение для следующего запроса
        sendAjax(url); // передаём необходимые данные функции отправки запроса

    }, 30);
}


function sendAjax(url) {
    // showLoaderIdenity(); //  показываем идентификатор загрузки
    $.ajax({ //  сам запрос
        type: 'GET',
        url: url,
        dataType: "json" // предполоижтельный формат ответа сервера
    }).done(function (res) { // если успешно
        // hideLoaderIdenity(); // скрываем идентификатор загрузки

        appendHtml(res.posts) // добавляем скаченные данные в конец ленты

        if (res.finished) { // если получили признак завершения прокрутки
            stopLoadTrying();
        }

        loadAjax = false; // укажем, что данный цикл загрузки завершён
        console.log('Ответ получен: ', res);

        if (res.success) { // если все хорошо
            console.log('ОК!)');

        } else { // если не нравится результат
            console.log('Пришли не те данные!');
            alert(res.message);
        }
    }).fail(function () { // если ошибка передачи
        //hideLoaderIdenity();
        loadAjax = false;
        console.log('Ошибка выполнения запроса!');
    });
}

/**
 * Добавим подгруженные данные в ленту
 *
 * @param {type} html
 * @returns {undefined}
 */
function appendHtml(posts) {

    for(var i=0; i< posts.length; i++){
        var html = '<div class="post">' + posts[i].user + posts[i].message + '</div>';
        $(projectsContainerId).append(html);
    }


}


function stopLoadTrying() {
    $(window).off('scroll'); // отвязываем обработку прокрутки от окна
}
