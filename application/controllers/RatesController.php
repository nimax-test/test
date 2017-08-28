<?php

// API для получения курсов валют
class RatesController extends Zend_Controller_Action {

    // курсы валют выводимые по умолчанию
    protected $codes = ['USD', 'EUR', 'BYN', 'UAH',];
    // источник данных для котировок
    protected $source;

    // инициация моделей
    public function init() {
        Zend_Loader::loadClass('Rates');
        Zend_Loader::loadClass('Kazahbank');
        Zend_Loader::loadClass('Centrobank');
        $this->source = new Centrobank; // источник
        //  ы$this->source = new Kazahbank; // другой источник
    }

    public function indexAction() {
        $this->getRates(); // локальные данные
    }

    public function updateAction() {
        $this->getRates(true); // первоисточник
    }

    // общий функционал получения данных
    public function getRates($update = false) {
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
        $this->view->rates = $data; // данные для вывода    
        $this->view->codes = $this->_getParam('codes') ?: $this->codes;
        $this->view->comment = "{$date} - {$this->source->title} {$update}";
        $this->render('index'); // используем один шаблон для вывода данных
    }

}
