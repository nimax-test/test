<?php

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Ensure library && all classes is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/models'),
    realpath(APPLICATION_PATH),
    get_include_path(),
)));

// Zend_Application
require_once 'Zend/Application.php';

// Create application & bootstrap
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');

// MySQL database connection params
$db = Zend_Db::factory('pdo_mysql', array(
            'host' => 'localhost',
            'username' => 'test',
            'password' => 'test',
            'dbname' => 'test'
        ));
Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set('db', $db);

// Run application
$application->bootstrap()->run();
