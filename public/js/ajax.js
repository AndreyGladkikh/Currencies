'use strict';

document.addEventListener('DOMContentLoaded', function(){
    let fromCurrencyId = '',
        toCurrencyId = '',
        price = null,
        converter = null,
        token = document.querySelector('input[name=_token]').value;

    /**
     * Устанавливает из какой в какую валюту будет происходить конвертация
     *
     * @param fromFieldId
     * @param toFieldId
     */
    let setCurrencyIds = (fromFieldId, toFieldId) => {
        fromCurrencyId = document.getElementById(fromFieldId).value;
        toCurrencyId = document.getElementById(toFieldId).value;
    };

    /**
     * Задает параметры конвертера
     */
    let setConverterParams = () => {
        converter.setPrice(price)
            .setFromCurrencyId(fromCurrencyId)
            .setToCurrencyId(toCurrencyId);
    };


    class Converter {
        constructor(converterUrl, token) {
            this.converterUrl = converterUrl;
            this.token = token;
            this.price = null;
            this.fromCurrencyId = '';
            this.toCurrencyId = '';
            this.resultField = '';
        }
        setFromCurrencyId(fromCurrencyId) {
            this.fromCurrencyId = fromCurrencyId;
            return this;
        }
        setToCurrencyId(toCurrencyId) {
            this.toCurrencyId = toCurrencyId;
            return this;
        }
        setPrice(price) {
            this.price = price;
            return this;
        }
        setResultField(resultField) {
            this.resultField = resultField;
            return this;
        }

        /**
         * Заголовки, передаваемые в запросе
         *
         * @returns {{Content-Type: string, X-CSRF-TOKEN: *}}
         */
        getDefaultHeaders() {
            return {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.token
            };
        }

        /**
         * Обработчик ответа
         *
         * @param response
         * @returns {boolean}
         */
        handleResponse(response) {
            if (response.status === 200) {
                document.getElementById(this.resultField).value = response.result;
                return true;
            }
            this.handleError(response);
        }
        handleError(error) {
            console.warn(error.message);
        }

        /**
         * Проверяет наличие необходимых параметров
         */
        convert() {
            this.price && this.fromCurrencyId && this.toCurrencyId && this.resultField &&
                this.sendRequest();
        }
        sendRequest() {
            let data = JSON.stringify({
                'price': this.price,
                'from': this.fromCurrencyId,
                'to': this.toCurrencyId
            });
            fetch(this.converterUrl, {
                method: 'POST',
                headers: this.getDefaultHeaders(),
                body: data
            }).then((response) => response.json())
                .then((response) => this.handleResponse(response));
        }
    }

    converter = new Converter(
        '/convert',
        token
    );

    /**
     * Функция срабатывают при изменении содержимого левого поля
     * price - введенная сумма
     *
     * @param event
     */
    document.getElementById('leftPrice').onchange = function(event){
        price = event.target.value;
        setCurrencyIds('leftSelect', 'rightSelect');
        setConverterParams();
        converter.setResultField('rightPrice').convert();
    };

    /**
     * Функция срабатывают при изменении содержимого правого поля
     *
     * @param event
     */
    document.getElementById('rightPrice').onchange = function(event){
        price = event.target.value;
        setCurrencyIds('rightSelect', 'leftSelect');
        setConverterParams();
        converter.setResultField('leftPrice').convert();
    };

});