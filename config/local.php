<?php
/**
 * Created by PhpStorm.
 * User: rolian85
 * Date: 9/18/14
 * Time: 11:33 PM
 */

return array(
    'debug' => true,
    'phpSettings' => array(
        'error_reporting' => 1
    ),
    'cache' => array(
        'enabled' => false,
    ),
    'cors' => array(
        'enabled' => true,
        'origin' => '*'
    ),
    'doctrine' => array(
        'default' => array(
            'mapping' => array(
                'cache' => false,
            ),
            'connection' => array(
                'host'     => '127.0.0.1',
                'user'     => 'root',
                'password' => '',
                'dbname'   => 'default_db',
            )
        )
    )
);