<?php 
//BANCO LOCAL
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'pivos';//*/
try{$pdo=new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8","$usuario","$senha");} catch(Exception $e){echo $e->getMessage();}