<?php

// курсы Казахстана
class Kazahbank extends Xml {

    // таблица полей
    protected $values = [
        'code' => 'title',
        'nominal' => 'quant',
        'name' => 'fullname',
        'value' => 'description',
    ];
    // наименование источника
    public $title = 'Kazahbank';
    // url c котировками валют Нацбанка Казахстана
    protected $url = 'http://www.nationalbank.kz/rss/get_rates.cfm';

}
