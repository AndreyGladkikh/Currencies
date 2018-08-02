'use strict';
class Converter {
    setFromValute(direction)
    {
        this.from = direction === "leftToRight" ? document.getElementById('leftSelect').value : document.getElementById('rightSelect').value;
        this.fromName = direction === "leftToRight" ? "leftPrice" : "rightPrice";
        this.fromField = document.getElementById(this.fromName);
    }

    setToValute(direction)
    {
        this.to = direction === "leftToRight" ? document.getElementById('rightSelect').value : document.getElementById('leftSelect').value;
        this.toName = direction === "leftToRight" ? "rightPrice" : "leftPrice";
        this.toField = document.getElementById(this.toName);
    }

    getConvertResult()
    {
        var request = new XMLHttpRequest(),
            csrfToken = document.querySelector('input[name=_token]').value,
            params = 'price=' + this.fromField.value + '&from=' + this.from + '&to=' + this.to;
        request.open('POST' , 'convert');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        request.onreadystatechange = function() {
            if(request.readyState === XMLHttpRequest.DONE && request.status === 200){
                var responseObject = JSON.parse(request.response);
                this.toField.value = responseObject.result;
            }
        }
        request.send(params);
    }
}