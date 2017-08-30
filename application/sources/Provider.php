<?php

class Provider {

    public $id; // id кэша
    public $data; // данные
    public $cache; // кэш Zend
    public $status; // результат
    protected $source; // источник

    // подключение нужных источников
    public function __construct() {
        Zend_Loader::loadClass('Xml'); // Xml
        Zend_Loader::loadClass('Rates'); // таблица
        Zend_Loader::loadClass('Kazahbank'); // источник
        Zend_Loader::loadClass('Centrobank'); // источник

        $config = 'configs/application.ini'; // из конфига
        $config = new Zend_Config_Ini($config); // дефолтный
        $default = $config->development->defaultsource; // источник
        $this->source = $default ? new Centrobank : new Kazahbank; // ЦБ

        $this->caсhe = Zend_Registry::get('cache'); // подключаем Zend кэш
    }

    // функционал загрузки и обновления данных
    public function updateData($update = false) {
        $this->date = date('Y-m-d'); // текущая дата и id для кэша
        $this->id = str_replace("-", "", $this->date) . $this->source->title;

        if ($update) { // по запросу
            $this->reloadData(); // берем с источника
        }

        $data = $this->loadData(); // загружаем локально

        if (!count($data)) { // заново
            $update = true; // при необходимости
            $this->reloadData(); // берем данные с источника
            $data = $this->loadData(); // и загружаем локально
        }

        $update = $update ? '- Update' : ''; // статус
        $this->data = $data; // данные для вывода пользователю
        $this->status = "{$this->date} - {$this->source->title} {$update}";
    }

    // загрузка из кэша/базы
    protected function loadData() {
        // сперва проверям данные в кэше
        if (!$data = $this->caсhe->load($this->id)) {
            $rates = new Rates; // а иначе грузим из базы данных
            $data = $rates->getData($this->source->title, $this->date);
            $this->caсhe->save($data, $this->id); // и записываем в кэш
        }
        return $data;
    }

    // загрузка из источника
    protected function reloadData() {
        $rates = new Rates; // пишем в базу данных
        $data = $this->source->getData($this->date); // из источника
        $rates->updateData($this->source->title, $this->date, $data);
        $this->caсhe->remove($this->id); // убираем старые данные из кэша
    }

}
