<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Transaction;
use App\Operation;
use Validator;

class CurrenciesController extends Controller
{
    const STATUS_OK = 200;
    const STATUS_ERROR = 400;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = $this->getAllCurrencies();
        $valuteProps = get_object_vars($data->Valute);
        $operations = Operation::orderBy('created_at', 'desc')->limit(5)->get();
//        return view('currencies', [
//            'valuteProps' => $valuteProps,
//            'operations' => $operations
//        ]);
        return view('currencies', compact('valuteProps', 'operations'));
    }

    /**
     * Обрабатывает ajax-запрс, возвращает полученную на вход сумму, конвертированную в другую валюту
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function convert(Request $request)
    {
        $priceValidator = Validator::make($request->all(), [
            'price' => 'required|digits_between:0, 10',
                'from' => 'required',
                'to' => 'required'
            ]
        );
        if ($priceValidator->fails()) {
            return response()->json([
                'status' => self::STATUS_ERROR,
                'message' => 'Поле должно содержать не более 10 цифр',
            ], self::STATUS_ERROR);
        }

        $price = (int)$request->price;
        $from = (string)$request->from;
        $to = (string)$request->to;

        $priceConverted = $this->getConvertedPrice(
            $price,
            $this->getCurrencyById($from),
            $this->getCurrencyById($to));

        Operation::create([
            'from_currency_id' => $from,
            'from_price' => $price,
            'to_currency_id' => $to,
            'to_price' => $priceConverted
        ]);

        return response()->json([
            'status' => self::STATUS_OK,
            'result' => $priceConverted,
        ], self::STATUS_OK);
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
     * Возвращает курсы валют текущего часа
     *
     * @return string
     */
    private function getAllCurrencies()
    {
        $json_daily_file = base_path().'/currencies/daily.json';
        if (!is_file($json_daily_file) || filemtime($json_daily_file) < time() - 3600) {
            $this->updateCurrencies();
        }
        return json_decode(file_get_contents(base_path().'/currencies/daily.json'));
    }

    /**
     * Возвращает стоимость валюты в рублях
     *
     * @param $id
     * @return int
     */
    private function getCurrencyById($id)
    {
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
    private function getConvertedPrice($price, $from, $to)
    {
        return round($price * $from / $to, 2);
    }
}
