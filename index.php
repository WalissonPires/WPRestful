<?php

require_once "/wprestful/WPRestful.php";
require_once "/wprestful/BaseController.php";
require_once "/CustomController.php";

$api = new WPRestful();

$api->setNotfound(function() {
	echo "Ação inválida!  [DO CALLBACK]";
});

//-------------------------------------- USE CUSTOM CONTROLLER ---------------------------------------------------

$api->addController(new CustomController());
//----------------------------------------------------------------------------------------------------------------


//-------------------------------------- USE BASE CONTROLLER ---------------------------------------------------

$controller = new BaseController();
$controller->addGet("/Agente/{p}", function($sender, $id) {
	echo "<br/> ID: ".$id;

});

$controller->addGet("/Agente/{p}/Categoria/{p}/Posicao/{p}", function($sender, $id, $categoria, $posicao) {
	echo "<br/> ID: ".$id."<br/> Categoria: ".$categoria."<br/> Posição: ".$posicao;
});

$api->addController($controller);
//----------------------------------------------------------------------------------------------------------------



$api->handler();
?>