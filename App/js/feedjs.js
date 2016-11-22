$(function(){
    
    var file;
    
    // Обработка события - изменения поля input=file
    $('input[type=file]').change(function(){
    
        file = this.files;
    });
    
    // Обработка клика по кнопке предварительного просмотра
    $('button#btn_preview').click(function(event){
        
        event.stopPropagation();    // Остановка происходящего
        event.preventDefault();
        
        var data = new FormData();
        $.each(file, function(key, value){
            data.append(key, value);
        });
        
        var form = $('form#feedback').serializeArray();
        $.each(form, function(key, obj){
            
            data.append(obj.name, obj.value);
        });
        
        // Отправка запроса
        
        $.ajax({
            url:'/page/preview',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'html',
            processData: false, // Не обрабатываем файлы
            contentType: false, // это строковый запрос
            success: function (respond, textStatus, jqXHR){
                
                // Если запрос выполнен успешно
                if (typeof respond.error === 'undefined'){
                    
                    $('div#preview').html(respond);
                }
                else {
                    console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                
                console.log('ОШИБКИ AJAX запроса: ' + textStatus);
            }
        });
        
    });
});
