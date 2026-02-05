BX.ready(function() {
    // Ждем загрузки интерфейса CRM
    if (typeof BX.Crm.EntityEditor !== 'undefined') {
        // Регистрируем вкладку
        BX.Crm.EntityEditor.registerTab({
            id: 'custom_hello_tab',
            title: 'Привет мир',
            icon: 'fa fa-smile-o',
            enabled: true,
            render: function(container) {
                // Создаем контент вкладки
                var content = document.createElement('div');
                content.innerHTML = '<div style="padding: 20px; font-size: 18px; color: #333;">Привет мир</div>';
                container.appendChild(content);
            }
        });
    }
});