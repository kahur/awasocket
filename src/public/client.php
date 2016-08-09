<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once '../../vendor/autoload.php';

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//socket_con
socket_connect($socket, '127.0.0.1', 9400);

socket_write($socket, 'Ahoj', strlen('Ahoj'));




