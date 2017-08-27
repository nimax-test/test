<?php

// API для получения курсов валют
class RatesController extends Zend_Controller_Action {

    // инициация класса
    public function init() {
        Zend_Loader::loadClass('CbXml');
    }

    public function indexAction() {
        $this->getRates(); // данные из кэша
    }

    public function updateAction() {
        $this->getRates(true); // данные из ЦБ
    }

    // общий функционал получения данных
    private function getRates($update = false) {
        $cbrf = new CbXml; // курсы валют Центробанка
        $codes = $this->_getParam('codes'); // по списку
        $this->view->rates = $cbrf->getRates($codes, $update);
        $this->view->comment = $cbrf->comment; // источник данных
        $this->render('index'); // используем один шаблон для вывода        
    }

}
