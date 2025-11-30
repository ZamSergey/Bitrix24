BX.addCustomEvent("onTimeManDataRecieved", function (data) {

    BX.defer(() => {
        let button = document.querySelector('#bx-avatar-header-popup button.ui-btn.--air.tm-control-panel__action.ui-btn-lg.--wide');
        let customButton = document.querySelector('custom_button');
        let isPause = button ? button.querySelector('.ui-btn-text-inner').textContent === "Пауза" : false;
        
        if (button && !customButton) {            
            let copyButton = button.cloneNode(true);           
            if (!isPause) {
                button.setAttribute('style', 'position: absolute; top: -10000px; left: -10000px');
                copyButton.classList.add('custom_button');
                copyButton.addEventListener('click', (e) => customClickHandler(e, button))
                button.parentNode.append(copyButton);
            }
        }

    })();

});

const customClickHandler = (e, button) => {
    console.log('Custom click');
    // showForm();
    // Confirmer.show();
    popUpWindow(button);
}


const popUpWindow = (button) => {


    BX.PopupWindowManager.create("bookingPopup_", null, {
        content: "<div>Вы точно уверены, что хотите нажать эту кнопку?</div>",
        titleBar: { content: BX.create("span", { html: "Подтверждение действия пользователя" }) },
        closeIcon: { right: "20px", top: "10px" },
        width: 400,
        height: 200,
        zIndex: 100,
        closeIcon: {
            // объект со стилями для иконки закрытия, при null -иконки не будет
            // opacity: 1
        },
        titleBar: "Подтвердите свое действие",
        closeByEsc: true, // закрывать при нажатии на Esc
        darkMode: false, // окно будет светлым или темным
        autoHide: false, // закрытие при клике вне окна
        draggable: true, // можно двигать или нет
        resizable: true, // можно изменят размер
        min_height: 100, // минимальная высота окна
        min_width: 100, // минимальная ширина окна
        lightShadow: false, // использовать светлую тень у окна
        angle: false, // появится уголок
        overlay: {
            // объект со стилями фона
            backgroundColor: "black",
            opacity: 400
        },
        buttons: [
            new BX.PopupWindowButton({
                text: "Конечно, я не хочу, но надо...", // текст кнопки
                id: "confirmDay", // идентификатор
                events: {
                    click: function () {
                        console.log("Пришел родименький");
                        button.click();
                        BX.PopupWindowManager.getCurrentPopup().destroy(); // удаление из DOM-дерева после закрытия                             
                        BX.PopupWindowManager.getCurrentPopup().close();

                    }
                }
            }),
            new BX.PopupWindowButton({
                text: "Нет!", // текст кнопки
                id: "denyDay", // идентификатор
                events: {
                    click: function () {
                        console.log("Отменил начало дня....");

                        BX.PopupWindowManager.getCurrentPopup().destroy(); // удаление из DOM-дерева после закрытия                             
                        BX.PopupWindowManager.getCurrentPopup().close();

                    }
                }
            })
        ]
    }).show();


}




