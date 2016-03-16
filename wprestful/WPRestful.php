<?php

require_once "/BaseController.php";
require_once "/URIAction.php";

class WPRestful {
	
	private $controllers = array();
	
	private function create_pattern($uri)
	{
		$elements = explode("/", $uri);
		unset($elements[0]);
		
		$pattern = "";
		
		if (count($elements) > 0)
		{
				$pattern .= "/^";
				
				foreach($elements as $item)
				{
					$pattern .= "\/";
					
					if (preg_match("/^{p}$/", $item))
					{
						//var item
						$pattern .= "\w+";
					}
					else 
					{
						//const string
						$pattern .= $item;
					}
				}
				
				$pattern .= "$/";
		}
		
		return $pattern;
	}
	
	private function extract_params_uri($action_uri, $req_uri)
	{	
		$elements = explode("/", $action_uri);
		unset($elements[0]);
		$elements = array_values($elements);
		
		$params = explode("/", $req_uri);
		unset($params[0]);
		$params = array_values($params);
				
		$extracted_params = array();
		
		
		for($i = 0; $i < count($elements); $i++)
		{		
			if (preg_match("/^{p}$/", $elements[$i]) === 1)
				$extracted_params[] = $params[$i];
		}
		
		return $extracted_params;
	}
	
	public function setNotFound($func)
	{
		$this->funcNotFound = $func;
	}
	
	public function addController($controller)
	{
		$controller->createActions();
		$this->controllers[] = $controller; 
	}
	
	public function handler()
	{		
		$req_method = $_SERVER['REQUEST_METHOD'];
		$req_uri = $_SERVER['REQUEST_URI'];
		$req_uri = "/".explode("/", $req_uri, 3)[2];
		$sel_action = null;
		
	
		foreach($this->controllers as $controller)
		{
			foreach($controller->getURIActions() as $action)
			{
				if (($action->Method === $req_method) and 
				(preg_match($this->create_pattern($action->Uri), $req_uri) === 1))
				{
					$sel_action = $action->getAction();
					break;
				}
			}
			
			if ($sel_action != null)
				break;
		}
		
		if ($sel_action != null)
		{
			$uri_params = $this->extract_params_uri($action->Uri, $req_uri);
			
			array_unshift($uri_params, $this);	

			call_user_func_array($sel_action, $uri_params);
		}
		else
		{
			if (isset($this->funcNotFound))
			{
				$func = $this->funcNotFound;
				$func();
			}
			else
				throw new Exception("Not found function not defined.");
		}
	}
}
?>