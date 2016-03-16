<?php

/*
	Esta classe server para gerenciar uma conjunto de URI válidas.
	A partir dela que é definido quais serão as URI válidas e a qual 
	método a mesma irá responser e qual será a ação executada.
	
	
	A URI deve iniciar uma uma barra (/) e pode conter constantes e parâmetros.
	
	Exemplos de URIs aceitas:
	
	  /Produto/1		     		(Onde "1" é um parâmetro)
	  /Produto/Categoria/A   		(Onde "A" é um parâmetro)
	  /Categoria/A/SubCategoria/B	(Onde "A" e "B" são parâmetros)
	  
	 
	Exemplo de URIs inválidas
	
	  Produto/1
	  
	
	Para diferenciar uma constante de um parâmetro deve-se usa chaves ({}) da
	definição da URI. O padrão de URI para os exemplos acima podem ser 
	definidos da seguinte forma:
	
	  /Produto/{id}
	  /Produto/Categoria/{cat}
	  /Categoria/{categ}/SubCategoria/{subCateg}
	  
	Os valores dentro das chaves não são relevantes. Portanto, aconselha-se usar
	nomes significativos apenas para melhor leitura do desenvolvedor.
	  
	Por exemplo:  /Produto/{v}  ,  /Produto/{identificador} ,    /Produto/{id}
	temos o mesmo efeito.  Todos dizem que naquela posição da URI terá uma parâmetro.
	  
	Entretanto não se pode usar as chaves vazias. O seguinte exemplo está incorreto:
	  
	  /Produto/{}


	A função de callback da URI deve possuir a seguinte assinatura
	
	function callback($sender [, $uriParam1 [,$uriParam2 [, ...]]]);
	
	Onde $sender é uma refêrencia para o objeto que realizou a chamada
	do callback.
	
	Os demais argumentos da função deve ser equivalente ao número de parâmetros na 
	URI.
	
	Como exemplo. A assinatura do callback da seguinte URI teria o seguinte formato.
	
	   /Categoria/{cat}/SubCategoria/{subCat}
	
	   function callback ($sender, $cat, $subCat) {}
	   
	Caso não haja parâmetros na URI o callback rebecera apenas o argumento $sender.
	
		/Produto
		
		function callback($sender) {}
		
		
	
	Utilize os método "add*" para adicionar ações da determinadas URIs.
	O sufixo do método irá definir qual método http a URI irá responder.
	
	
	addGet(...)  Responde a uma requisição GET.
	addPost(...) Responde a uma requisição POST.
	
	E o mesmo com os demais.
	
	
	Exemplo de utilização do método.
	
	addGet("/Produto/{id}", function($sender, $id) {
		echo "ID: " . $id; 
	});
	
*/

require_once "/URIAction.php";

class BaseController {
	
	private $actions = array();
	
	/*
		Usado por classes derivadas para criarem seuas ações.
		Caso o mesmo seja implementado deve-se retorna o valor TRUE.
	*/
	public function createActions()
	{
		return false;
	}
	
	public function addGet($uri, $func) {
		
		if ($this->valid_uri($uri)) {
		
			$action = new URIAction();
		
			$action->Method = "GET";
			$action->Uri = $uri;
			$action->setAction($func);
		
			//add action in vector
			$this->actions[] = $action;
		}
		else
			throw new Exception("Uri inválida");
	}
	
	public function getURIActions()
	{
		return $this->actions;
	}
	
	/*
		URI pattern:  /const/const/{p}/const/{p}
		
		const: Value compuse of characteres:  A-Z e 0-9.  (no case sensitive)
		{p}: Param position.
	*/
	private function valid_uri($uri)
	{
		return (preg_match("/^((\/[A-Za-z0-9]+)+(\/{p})*)*$/", $uri) === 1);
	}
}
?>