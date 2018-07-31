'use strict';
document.addEventListener('DOMContentLoaded', function(){

    var convertCurrency = (direction) => {
        var request = new XMLHttpRequest(),
            csrfToken = document.querySelector('input[name=_token]').value,
            from = direction === "leftToRight" ? document.getElementById('leftSelect').value : document.getElementById('rightSelect').value,
            to = direction === "leftToRight" ? document.getElementById('rightSelect').value : document.getElementById('leftSelect').value,
            fromName = direction === "leftToRight" ? "leftPrice" : "rightPrice",
            toName = direction === "leftToRight" ? "rightPrice" : "leftPrice",
            converterUrl = from === "rub" ? "rubToUsd" : "usdToRub",
            fromField = document.getElementById(fromName),
            toField = document.getElementById(toName),
            p = `${fromName}=${fromField.value}`,
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

    document.getElementById('leftPrice').oninput = function(){
        convertCurrency('leftToRight');
    }

    document.getElementById('rightPrice').oninput = function(){
        convertCurrency('rightToLeft');
    }
});