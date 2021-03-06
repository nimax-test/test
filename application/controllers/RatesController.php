<?php

// API для получения курсов валют
class RatesController extends Zend_Controller_Action {

    // курсы валют выводимые по умолчанию
    protected $codes = ['USD', 'EUR', 'BYN', 'UAH',];
    // провайдер источников данных
    protected $provider;

    // инициация
    public function init() {
        Zend_Loader::loadClass('Provider'); // провайдер
        $this->provider = new Provider(); // источников данных
        $this->view->codes = $this->_getParam('codes') ?: $this->codes;
    }

    public function indexAction() {
        $this->provider->updateData(); // из кэша
        $this->view->rates = $this->provider->data; // данные
        $this->view->status = $this->provider->status; // статус
    }

    public function updateAction() {
        $this->provider->updateData(true); // обновляем
        $this->view->rates = $this->provider->data; // данные
        $this->view->status = $this->provider->status; // статус
        $this->render('index'); // используем один шаблон для вывода данных
    }

}
