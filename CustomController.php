<?php

require_once "/wprestful/BaseController.php";

class CustomController extends BaseController
{
	public function createActions()
	{
		$this->addGet("/Custom/Agente/{p}", function($sender, $id) {
			echo "<br/><h1>CUSTOM CONTROLLER</h1><br/> ID: ".$id;
		});

		$this->addGet("/Custom/Agente/{p}/Categoria/{p}/Posicao/{p}", function($sender, $id, $categoria, $posicao) {
			echo "<br/><h1>CUSTOM CONTROLLER</h1><br/> ID: ".$id."<br/> Categoria: ".$categoria."<br/> Posição: ".$posicao;
		});
		
		return true;
	}
}

?>