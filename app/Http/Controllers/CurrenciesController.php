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
        $data = $this->CBR();
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
        $data = $this->CBR();

        if($from === "RUB")
        {
            $priceConverted = round($price / $data->Valute->$to->Value, 2);
        }else if($to === "RUB")
        {
            $priceConverted = round($price * $data->Valute->$from->Value, 2);
        }else
        {
            $priceConverted = round($price * $data->Valute->$from->Value / $data->Valute->$to->Value, 2);
        }
        return \response()->json([
            'result' => $priceConverted,
        ]);
    }

    /**
     * API ЦБР, используется для получения курса валют
     *
     * @return mixed
     */
    public function CBR()
    {
        $json_daily_file = base_path().'/currencies/daily.json';
        if (!is_file($json_daily_file) || filemtime($json_daily_file) < time() - 3600) {
            if ($json_daily = file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js')) {
                file_put_contents($json_daily_file, $json_daily);
            }
        }

        return json_decode(file_get_contents($json_daily_file));
    }
}
