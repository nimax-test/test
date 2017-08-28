<?php

// курсы ЦБ XML
class Centrobank {

    // для базы данных
    public $title = 'Centrobank';
    // url для получения XML c котировками валют Центробанка
    protected $url = 'https://www.cbr-xml-daily.ru/daily.xml';

    // контруктор класса
    public function __construct() {
        
    }

    // запись в базу данных
    public function updateData($date) {
        $xml = simplexml_load_file($this->url);
        if ($xml) { // запишем данные в базу
            Zend_Loader::loadClass('Rates');
            $rates = new Rates; // таблица
            foreach ($xml as $rate) {
                $data[] = [
                    'date' => $date,
                    'name' => $rate->Name,
                    'source' => $this->title,
                    'code' => $rate->CharCode,
                    'nominal' => $rate->Nominal,
                    'value' => str_replace(",", ".", $rate->Value),
                ];
            }
            $rates->updateData($this->title, $date, $data);
        }
    }

}
