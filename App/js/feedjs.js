//
// AJAX-запрос от формы form_selector к обрботчику url для загрузки результатов
// в контейнер result_selector
//
function ajaxFormRequest(result_selector, form_selector, url)
{
    $.ajax({
        type: 'POST',
        url: url,
        contentType: 'multipart/form-data',
        dataType: 'html',
        data: $(form_selector).formSerialize(),
        cache: false,
        processData: false,
        success: function (response) {
            $(result_selector).html(response);
        },
        error: function (response) {
            $(result_selector).html('Ошибка при отправке формы!');
        }
    });
}
