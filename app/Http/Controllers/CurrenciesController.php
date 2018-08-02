<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CurrenciesController extends Controller
{
    /**
     * Возвращает представление главного окна
     * $valuteProps - массив валют с их свойствами
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->updateCurrencies();
        $data = $this->getAllCurrencies();
        $valuteProps = get_object_vars($data->Valute);
        return view('currencies', [
            'valuteProps' => $valuteProps,
        ]);
    }

    /**
     * Обрабатывает ajax-запрс, возвращает полученную на вход сумму, конвертированную в другую валюту
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function convert(Request $request)
    {
        $price = $request->price;
        $from = $request->from;
        $to = $request->to;

        return \response()->json([
            'result' => $this->getConvertedPrice($price, $this->getCurrencyById($from), $this->getCurrencyById($to)),
        ]);
    }

    /**
     * API ЦБР, используется для получения курса валют
     */
    public function updateCurrencies()
    {
        $json_daily_file = base_path().'/currencies/daily.json';
        if (!is_file($json_daily_file) || filemtime($json_daily_file) < time() - 3600) {
            if ($json_daily = file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js')) {
                file_put_contents($json_daily_file, $json_daily);
            }
        }
    }

    /**
     * Возвращает параметры валют
     *
     * @return mixed
     */
    public function getAllCurrencies()
    {
        return json_decode(file_get_contents(base_path().'/currencies/daily.json'));
    }

    /**
     * Возвращает стоимость валюты в рублях
     *
     * @param $id
     * @return int
     */
    public function getCurrencyById($id)
    {
        $this->updateCurrencies();
        $data = $this->getAllCurrencies();
        return ($id !== "RUB") ? $data->Valute->$id->Value : 1;
    }

    /**
     * Возвращает конвертированную сумму
     *
     * @param $price
     * @param $from
     * @param $to
     * @return float
     */
    public function getConvertedPrice($price, $from, $to)
    {
        return round($price * $from / $to, 2);
    }
}
