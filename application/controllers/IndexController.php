<?php

class IndexController extends Zend_Controller_Action { // фронт-контроллер

    public function init() {
        $this->view->baseUrl = $this->_request->getBaseUrl();
    }

    public function indexAction() {
        // index.php
    }

}