
<?php

class Xml { // курсы через XML

    public $title; // для базы данных
    protected $url; // url получения XML
    protected $values; // соответствие полей

    // получаем курсы валют

    public function getData($date) {
        $xml = simplexml_load_file($this->url);
        if ($xml) { // форматируем полученные данные
            foreach ($xml as $rate) { // для таблицы БД
                foreach ($this->values as $key => $value) {
                    $row[$key] = $rate->$value; // по валюте
                }
                $row['source'] = $this->title; // источник
                $row['date'] = $date; // дата
                $data[] = $row; // итого
            }
        }
        return isset($data) ? $data : [];
    }

}
