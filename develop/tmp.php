<?php
/**
 * Created by PhpStorm.
 * User: Lorenzo
 * Date: 06/12/2015
 * Time: 17:00
 */

$actual = date('Y-m-d');
$cant = +2;

echo "actual = ".$actual."<br>";

echo "actual + 2 dias = ".date('Y-m-d',strtotime($actual.$cant." days"));