<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CurrenciesController extends Controller
{
    public function index()
    {
        $data = $this->CBR();
        $valuteProps = get_object_vars($data->Valute);
//        echo "<pre>";
//        var_dump($valuteProps);
//        die();
        return view('currencies', [
            'data' => $data,
            'valuteProps' => $valuteProps,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertToRub(Request $request)
    {
        $priceUsd = $request->priceUsd;
        $data = $this->CBR();
        $priceRub = round($priceUsd * $data->Valute->USD->Value, 2);

        return \response()->json([
            'result' => $priceRub
        ]);
    }

    /**
     * конвертирует в доллары
     * @see http://www.cbr.ru
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertToUsd(Request $request)
    {
        $priceRub = $request->priceRub;
        $data = $this->CBR();
        $priceUsd = round($priceRub / $data->Valute->USD->Value, 2);

        return \response()->json([
            'result' => $priceUsd
        ]);
    }

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
