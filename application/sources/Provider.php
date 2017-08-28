<?php

class Provider {

    public $data; // данные
    public $status; // результат
    protected $source; // источник

    // подключение нужных источников

    public function __construct() {
        Zend_Loader::loadClass('Rates'); // таблица
        Zend_Loader::loadClass('Kazahbank'); // источник
        Zend_Loader::loadClass('Centrobank'); // источник

        $config = 'configs/application.ini';
        $config = new Zend_Config_Ini($config); // дефолтный
        $default = $config->development->defaultsource; // источник

        $this->source = $default ? new Centrobank : new Kazahbank; // ЦБ
    }

    // функционал загрузки и обновления данных
    public function updateData($update = false) {
        $date = date('Y-m-d'); // текущая дата
        if ($update) { // данные источника
            $this->source->updateData($date);
        }

        $rates = new Rates; // данные из базы
        $data = $rates->getData($this->source->title, $date);

        if (!count($data)) {
            $update = true; // обновляем
            $this->source->updateData($date);
            $data = $rates->getData($this->source->title, $date);
        }

        $update = $update ? '- Update' : '';
        $this->data = $data; // данные для вывода    
        $this->status = "{$date} - {$this->source->title} {$update}";
    }

}
