'use strict';
document.addEventListener('DOMContentLoaded', function(){

    var convertCurrency = (from, to) => {
        var request = new XMLHttpRequest(),
            csrfToken = document.querySelector('input[name=_token]').value,
            fromName = from === "rub" ? "priceRub" : "priceUsd",
            toName = to === "rub" ? "priceRub" : "priceUsd",
            converterUrl = from === "rub" ? "rubToUsd" : "usdToRub",
            fromField = document.getElementById(fromName),
            toField = document.getElementById(toName),
            params = `${fromName}=${fromField.value}`;

        request.open('POST' ,`/${converterUrl}`);
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

    document.getElementById('priceRub').oninput = function(){
        convertCurrency('rub', 'usd');
    }

    document.getElementById('priceUsd').oninput = function(){
        convertCurrency('usd', 'rub');
    }
});