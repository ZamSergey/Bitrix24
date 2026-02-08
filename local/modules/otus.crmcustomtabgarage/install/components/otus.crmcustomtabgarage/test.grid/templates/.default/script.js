BX.namespace('Otus.TestGrid');

let contactId = (function() {
    let path = window.location.pathname;
    let matches = path.match(/\/crm\/contact\/details\/(\d+)/);
    return matches && matches[1] ? parseInt(matches[1]) : 0;
})();

BX.Otus.TestGrid = {
    signedParams: null,
    init: function(data) {
        this.signedParams = data.signedParams;
    },
    showMessage: function (message) {
        alert(message);
    },
    deleteCar(id) {
        BX.ajax.runComponentAction('otus.crmcustomtabgarage:test.grid', 'deleteElement', {
            mode: 'class',
            signedParameters: BX.Otus.TestGrid.signedParams,
            data: {
                bookId: id,
            },
        }).then(response => {
            // BX.Otus.TestGrid.showMessage('Удалена книга с ID=' + id);
            let grid = BX.Main.gridManager.getById('BOOK_GRID2')?.instance;
            grid.reload();
        }, reject => {
            let errorMessage = '';
            for (let error of reject.errors) {
                errorMessage += error.message + '\n';
            }

            BX.Otus.TestGrid.showMessage(errorMessage);
        });
    },
   

    addCar: function () {
        BX.Otus.TestGrid.showForm();
    },

    showForm: function () {
        let oldPopup = BX.PopupWindowManager.getPopupById('book-add-form');
        if (oldPopup) {
            oldPopup.destroy();
        }

        let popup = BX.PopupWindowManager.create('book-add-form', null, {
            content:`
            <div style="padding: 20px; min-width: 400px;">
                <form id="book-add-form">
                    <div style="margin-bottom: 12px;">
                        <input type="text" 
                               name="carBrand" 
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;"
                               placeholder="Марка *"
                               >
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <input type="text" 
                               name="carModel" 
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;"
                               placeholder="Модель *"
                               >
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <input type="text" 
                               name="carColor" 
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;"
                               placeholder="Цвет">
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <input type="text" 
                               name="carNumber" 
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;"
                               placeholder="Гос. номер">
                    </div>
                    <div style="margin-bottom: 12px;">
                        <input type="number" 
                               name="carMile" 
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;"
                               placeholder="Пробег, км"
                               min="0">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <input type="number" 
                               name="carDate" 
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;"
                               placeholder="Год выпуска"
                               
                               >
                    </div>
                    
                    <input type="hidden" name="sessid" value="${BX.bitrix_sessid()}">
                    <input type="hidden" name="clientId" value="${contactId}">
                    
                    
                    
                    <input type="submit" value="Применить" style="position: absolute; opacity: 0">
                </form>
            </div>
        `,
            darkMode: true,
            buttons: [
                new BX.PopupWindowButton({
                    text: "Добавить машину" ,
                    className: "book-form-popup-window-button-accept" ,
                    events: {
                        click: function(){
                            let submit = document.querySelector('#book-add-form input[type="submit"]');
                            let form = document.getElementById('book-add-form');
                            form.removeEventListener('submit', BX.Otus.TestGrid.handleFormSubmit);
                        
                            // Добавляем новый обработчик
                            BX.Otus.TestGrid.handleFormSubmit = function(event) {
                                event.preventDefault();
                                BX.Otus.TestGrid.createBook(event.target);
                                this.popupWindow.close();
                            }.bind(this);
                            
                            form.addEventListener('submit', BX.Otus.TestGrid.handleFormSubmit);
                            submit.click();
                           
                            this.popupWindow.close();
                        }
                    }
                }),
                new BX.PopupWindowButton({
                    text: "Закрыть" ,
                    className: "book-form-button-link-cancel" ,
                    events: {
                        click: function(){
                            this.popupWindow.close();
                        }
                    }
                })
            ]
        });
        popup.show();
    },

    showCarDeal: function (id) {
            
        

        BX.ajax.runComponentAction('otus.crmcustomtabgarage:test.grid', 'getCarinfo', {
            mode: 'class',
            signedParameters: BX.Otus.TestGrid.signedParams,
            data: {
                bookId: id,
            },
            
        }).then(response => {
            console.log(response)
             BX.Otus.TestGrid.showCraInfoWindow(response)
            // let carInfo = response.data;
            // BX.Otus.TestGrid.showMessage('Вернуло данные=' + carInfo.car.brand);   
             if (response.status.success) {
                console.log('response.data.success')
                let carInfo = response.data;
                
            }         
            
        }, reject => {
            let errorMessage = '';
            for (let error of reject.errors) {
                errorMessage += error.message + '\n';
            }

            BX.Otus.TestGrid.showMessage(errorMessage);
        });

       
        

        
        
    },

    showCraInfoWindow: function(info) {
        let oldPopup = BX.PopupWindowManager.getPopupById('car-deal-info');
            if (oldPopup) {
                oldPopup.destroy();
            }   

            let popup = BX.PopupWindowManager.create('car-deal-info', null, {
            content:`
                   
                    <div style="padding: 20px; min-width: 400px;">
                        <h2>Информация о сделках по автомобилю ${info.data.car.brand} ${info.data.car.model} ${info.data.car.car_number} (Владелец: ${info.data.client.full_name})</h2>
                        ${BX.Otus.TestGrid.createCarDealTable(info.data.deals)}
                    </div>
                
            `,
                    darkMode: false,
                    buttons: [                
                        new BX.PopupWindowButton({
                            text: "Закрыть" ,
                            className: "book-form-button-link-cancel" ,
                            events: {
                                click: function(){
                                    this.popupWindow.close();
                                }
                            }
                        })
                    ]
                });
                popup.show();
    },

    createBook: function (form) {
        let data = new FormData(form);
        console.log(data)
        BX.ajax.runComponentAction('otus.crmcustomtabgarage:test.grid', 'addCar', {
            mode: 'ajax',
            data: data,
        }).then(response => {
            let id = response.data.BOOK_ID;
            // BX.Otus.TestGrid.showMessage('Добавлена книга с ID=' + id);
            let grid = BX.Main.gridManager.getById('BOOK_GRID2')?.instance;
            grid.reload();
        }, reject => {
            let errorMessage = '';
            for (let error of reject.errors) {
                errorMessage += error.message + '\n';
            }

            BX.Otus.TestGrid.showMessage(errorMessage);
        });
    },

    createCarDealTable: function(dealInfo) {
        let rows = '';
        const stageTitle = {'C1:NEW': 'Приемка','C1:PREPARATION': 'Диагностика','C1:PREPAYMENT_INVOICE': 'Ожидание запчастей','C1:EXECUTING': 'Ремонт','C1:FINAL_INVOICE': 'Проверка','C1:UC_DW6II2': 'Завершена'}
        dealInfo.forEach((deal, index) => {
            rows += `<tr>
                <td>${index + 1}</td>
                <td><strong>${deal.TITLE}</strong><br>
                    <small>ID: ${deal.ID} | Ответственный: <a href="${deal.ASSIGNED_BY.PROFILE_LINK}">${deal.ASSIGNED_BY.FULL_NAME}</a></small>
                </td>
                <td>${deal.DATE_CREATE}</td>
                <td>${stageTitle[deal.STAGE_ID]}</td>
                <td align="right"><strong>${parseFloat(deal.OPPORTUNITY).toLocaleString('ru-RU')}</strong></td>
                <td>${deal.PRODUCTS.map((p, i) => `${i + 1}. ${p}`).join('<br>')}</td>
            </tr>`;
        });  
        let html = `<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
                <thead>
                    <tr style="background: #f2f2f2;">
                        <th>№</th>
                        <th>Название сделки</th>
                        <th>Дата создания</th>
                        <th>Стадия</th>
                        <th>Сумма, руб.</th>
                        <th>Товары </th>
                    </tr>
                </thead>
                <tbody id="deals-body">${rows}</tbody>
            </table>`;
        
        return html;
    }
}