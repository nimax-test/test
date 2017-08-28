<?php

// XML-сервис
class Kazahbank {

    // для базы данных
    public $title = 'Kazahbank';
    // url c котировками валют Нацбанка Казахстана
    protected $url = 'http://www.nationalbank.kz/rss/get_rates.cfm';

    // конструктор класса
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
                    'code' => $rate->title,
                    'source' => $this->title,
                    'nominal' => $rate->quant,
                    'name' => $rate->fullname,
                    'value' => $rate->description,
                ];
            }
            $rates->updateData($this->title, $date, $data);
        }
    }

}
