<?php
namespace Currencies\Helpers;

class CurrencyConverter
{
    private $currencies;

    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;
    }

    public function getValuteProps()
    {
        $data = $this->currencies->getAllCurrencies();
        return get_object_vars($data->Valute);
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