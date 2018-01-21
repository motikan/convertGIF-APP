<?php
return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn'    => 'mysql:dbname=' . getenv('DBNAME') . ';host=' . getenv('DBHOST'),
        'username' => getenv('DBUSER'),
        'password' => getenv('DBPASS'),
        'driver_option' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
);