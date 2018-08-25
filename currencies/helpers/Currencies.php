<?php
namespace Currencies\Helpers;

class Currencies
{
    /**
     * Возвращает курсы валют текущего часа
     *
     * @return string
     */
    public function getAllCurrencies()
    {
        $json_daily_file = base_path().'/currencies/daily.json';
        if (!is_file($json_daily_file) || filemtime($json_daily_file) < time() - 3600) {
            $this->updateCurrencies();
        }
        return json_decode(file_get_contents(base_path().'/currencies/daily.json'));
    }

    /**
     * Запрашивает курсы валют в api цбр
     */
    private function updateCurrencies()
    {
        $json_daily_file = base_path().'/currencies/daily.json';
        if ($json_daily = file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js')) {
            file_put_contents($json_daily_file, $json_daily);
        }
    }

    /**
     * Возвращает стоимость валюты в рублях
     *
     * @param $id
     * @return int
     */
    public function getCurrencyById($id)
    {
        $data = $this->getAllCurrencies();
        return ($id !== "RUB") ? $data->Valute->$id->Value : 1;
    }
}