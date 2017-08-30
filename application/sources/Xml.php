
<?php

class Xml { // курсы через XML

    public $title; // для базы данных
    protected $url; // url получения XML
    protected $values; // соответствие полей

    // получение данных для базы

    public function getData($date) {
        $xml = simplexml_load_file($this->url);
        if ($xml) { // форматируем полученные данные
            foreach ($xml as $rate) { // для таблицы БД
                foreach ($this->values as $key => $value) {
                    $row[$key] = $rate->$value;
                }
                $row['source'] = $this->title;
                $row['date'] = $date;
                $data[] = $row;
            }
        }
        return isset($data) ? $data : [];
    }

}
