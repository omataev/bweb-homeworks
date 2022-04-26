<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

// Подключаем ядро Bitrix JS и расширение Ajax
CJSCore::Init(array('ajax'));
$sidAjax = 'testAjax';
if (isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax) {
    // Очистим буфер
    $GLOBALS['APPLICATION']->RestartBuffer();
    // Преобразуем массив PHP в js
    echo CUtil::PhpToJSObject(array(
        'RESULT' => 'HELLO',
        'ERROR' => ''
    ));
    // Умираем
    die();
}

?>
<div class="group">
    <div id="block"></div>
    <div id="process">wait ...</div>
</div>
<script>
    // Устанавливаем режим отладки для вывода отладочной информации
    window.BXDEBUG = true;

    // Загружаем данные
    function DEMOLoad() {
        // Скрываем DOM-элемент block
        BX.hide(BX("block"));
        // Показываем DOM-элемент process
        BX.show(BX("process"));
        /*  Загружаем json-объект из GET-запроса  (первый параметр)
        и передаем результат в функцию DEMOResponse */
        BX.ajax.loadJSON(
            '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
            DEMOResponse
        );
    }

    // Выводим полученные данные из запроса
    function DEMOResponse(data) {
        // Выводим отладочную информацию
        BX.debug('AJAX-DEMOResponse ', data);
        // Устанавливаем в DOM-элемент block входной параметр функции
        BX("block").innerHTML = data.RESULT;
        // Выводим DOM-элемент block
        BX.show(BX("block"));
        // Прячем DOM-элемент process
        BX.hide(BX("process"));
        // Вызываем обработчик события DEMOUpdate для объекта BX(BX("block"))
        BX.onCustomEvent(
            BX(BX("block")),
            'DEMOUpdate'
        );
    }

    /*
        Проверяем загрузку DOM
        Добавляем обработчик события «DOM-структура доступна для записи»
     */
    BX.ready(function () {
        /*
        BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
           window.location.href = window.location.href;
        });
        */
        // Прячем DOM-элемент block
        BX.hide(BX("block"));
        // Прячем DOM-элемент process
        BX.hide(BX("process"));

        /*
        Устанавливает обработчик события click на дочерние элементы узла body
        с именем класса css_ajax
        */
        BX.bindDelegate(
            document.body, 'click', {className: 'css_ajax'},
            function (e) {
                if (!e)
                    e = window.event;

                DEMOLoad();
                // Переопределяем действия браузера по умолчанию
                return BX.PreventDefault(e);
            }
        );

    });

</script>
<div class="css_ajax">click Me</div>
<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
