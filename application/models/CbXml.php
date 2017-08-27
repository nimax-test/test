<?php

// ЦБ XML
class CbXML {

    // комментарий
    public $comment;
    // курсы валют XML
    protected $xml = [];
    // список нужных валют
    protected $default = ['USD', 'EUR', 'BYN', 'UAH',];
    // url для получения XML c котировками валют Центробанка
    protected $url = 'https://www.cbr-xml-daily.ru/daily.xml';

    // контруктор класса
    public function __construct() {
        
    }

    // получение списка вылют
    public function getRates($codes = [], $update = false) {
        $rates = []; // список валют
        $this->getXML($update); // получаем XML
        $codes = ($codes ?: $this->default); // коды
        foreach ($this->xml as $rate) { // выборка валют
            $rate->Show = in_array($rate->CharCode, $codes);
            $rates[] = $rate; // конечный список
        }
        return $rates;
    }

    // запись в базу данных
    private function addRates() {
        Zend_Loader::loadClass('Rates');
        $rates = new Rates; // таблица в базе
        $rates->addXml($this->xml); // запись XML
    }

    // данные Центробанка
    private function getXML($update = false) {
        $date = date('Y/m/d - h:m:s'); // актуальность
        $file = '../public/doc/' . date('Y-m-d') . '.xml';
        // проверяем наличие кэшированной XML
        if ((!$update) && file_exists($file)) {
            $this->xml = simplexml_load_file($file);
            $this->comment = "{$date} - Data From Cache";
        } else { // читаем данные из Центробанка и кэшируем
            $xml = simplexml_load_file($this->url);
            if ($xml) { // все в порядке
                $this->comment = "{$date} - Centrobank";
                $xml->asXML($file); // пишем в кэш
                $this->xml = $xml; // данные XML
                $this->addRates(); // в базу
            } else { // сбой при получении
                $this->comment = "{$date} - Error XML";
                $this->xml = []; // пустые данные
            }
        }
    }

}
