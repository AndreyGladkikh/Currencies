'use strict';
document.addEventListener('DOMContentLoaded', function(){

    /**
     * direction - напрвление конвертации - из левого поля в правое или наоборот
     * from, to - аббревиатуры валют
     * fromName, toName - id полей
     * fromField, toField - дескрипторы полей
     * params - параметры, передаваемые в CurrenciesController@convert
     *
     * @param direction
     */
    var convertCurrency = (direction) => {
        var request = new XMLHttpRequest(),
            csrfToken = document.querySelector('input[name=_token]').value,
            from = direction === "leftToRight" ? document.getElementById('leftSelect').value : document.getElementById('rightSelect').value,
            to = direction === "leftToRight" ? document.getElementById('rightSelect').value : document.getElementById('leftSelect').value,
            fromName = direction === "leftToRight" ? "leftPrice" : "rightPrice",
            toName = direction === "leftToRight" ? "rightPrice" : "leftPrice",
            fromField = document.getElementById(fromName),
            toField = document.getElementById(toName),
            params = 'price=' + fromField.value + '&from=' + from + '&to=' + to;

        request.open('POST' , 'convert');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        request.onreadystatechange = function() {
            if(request.readyState === XMLHttpRequest.DONE && request.status === 200){
                var responseObject = JSON.parse(request.response);
                toField.value = responseObject.result;
            }
        }
        request.send(params);
    }

    //var myConverter = new Converter();

    /**
     * Две аналогичные функции, срабатывают при изменении содержимого левого и правого поля соответственно
     */
    document.getElementById('leftPrice').oninput = function(){
        convertCurrency('leftToRight');
        // myConverter.setFromValute('leftToRight');
        // myConverter.setToValute('leftToRight');
        // myConverter.getConvertResult();
    }

    document.getElementById('rightPrice').oninput = function(){
        convertCurrency('rightToLeft');
        // myConverter.setFromValute('rightToLeft');
        // myConverter.setToValute('rightToLeft');
        // myConverter.getConvertResult();
    }
});