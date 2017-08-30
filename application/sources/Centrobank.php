<?php

// курсы Центробанка
class Centrobank extends Xml {

    // таблица полей
    protected $values = [
        'name' => 'Name',
        'value' => 'Value',
        'code' => 'CharCode',
        'nominal' => 'Nominal',
    ];
    // наименование источника
    public $title = 'Centrobank';
    // url для получения XML c котировками Центробанка
    protected $url = 'https://www.cbr-xml-daily.ru/daily.xml';

}
