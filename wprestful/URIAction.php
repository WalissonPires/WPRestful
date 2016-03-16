<?php

/*
	Este classe serve para descrever as URIs válidas (que a api irá responder),
	o método aceito pela URI e qual ação a ser executa ao receber uma requisição.
*/

class URIAction {
	
	public $Method;
	public $Uri;
	
	public function setAction($func)
	{
		$this->func = $func;
	}
	
	public function getAction()
	{
		return $this->func;
	}
}
?>