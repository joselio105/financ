<?php
class Linker {
	
	private $id;
	private $class;
	private $title;
	private $newWindow;
	private $tooltip;
	private $tooltipMsg;
	private $content;
	private $action;
	private $controller;
	private $params;
	private $link;
	private $rel;
	private $permition;
	private $executable;
	private $visible;
	
	public function __construct($action, $content, array $params=NULL, $controller=NULL){
		$this->action = $action;
		$this->controller = (!is_null($controller) ? $controller : HelperNavigation::getController());
		$this->params = $params;
		$this->setLink();
		$this->setContent($content);
		$this->class[0] = 'tooltip';
		$this->newWindow = FALSE;
		$this->tooltip = TRUE;
		$this->permition = HelperAuth::getPermitionByType(PERMITION_LEVEL_ADMIN);
		$this->executable = TRUE;
		$this->visible = NULL;
		$this->rel = 'nofollow';
	}
	
	public function __toString(){
		
		$string = '';
		
		if($this->getVisible()){
			if($this->executable)
				$string = "\n<a href=\"{$this->getLink()}\" {$this->getClass()}{$this->getId()}{$this->getNewWindow()}{$this->getTitle()}rel=\"{$this->rel}\">";
			else 
				$string.= "<span {$this->getClass()}{$this->getId()}>";
			$string.= "\n\t{$this->getContent()}";
			if($this->executable){
				$string.= "\n\t{$this->getTooltip()}";
				$string.= "\n</a>";
			}else 
				$string.= "</span>";
		}
		
		return $string;
	}
	
	/**
	 * Faz o link ficar visÃ­vel caso mesmo sem permissÃ£o
	 */
	public function disableIfNoPermition(){
		if(!$this->getPermition())
			$this->setDisabled();
	}
	
	/**
	 * Torna o link desabilitado
	 */
	public function setDisabled(){
		$this->setClass('disabled');
		$this->setNoExecutable();
		$this->visible = TRUE;
	}
	
	/**
	 * Determina a url do link
	 * @param string $url
	 */
	public function setLink($url=NULL){
		$param = array();
		if(!is_null($this->params)){
			foreach ($this->params as $key=>$value)
				$param[] = (URL_FRIENDLY ? "{$key}/{$value}" : "{$key}={$value}");
		}
		$param = (!empty($param) ? (URL_FRIENDLY ? implode('/', $param).'/' : '&'.implode('&', $param)): NULL);
		
		if(!is_null($url)){
			$this->link = $url;
			$this->rel = 'external';
		}else
			$this->link = (URL_FRIENDLY ? URI."{$this->controller}/{$this->action}/{$param}" : "?ctrl={$this->controller}&act={$this->action}{$param}");
		
	}
	
	/**
	 * Determina o id do link
	 * @param string $id
	 */
	public function setId($id){
		$this->id = $id;
	}
	
	/**
	 * Determina a classe do link
	 * @param string $class
	 */
	public function setClass($class){	
		foreach (explode(' ', $class) as $c):
			$key = count($this->class)-1;
			$this->class[$key] = $c;
		endforeach;			
	}
	
	/**
	 * Determina que o link abrirÃ¡ em uma nova janela
	 */
	public function setNewWindow(){
		$this->newWindow = TRUE;
		$this->rel = 'noopener';
	}
	
	/**
	 * Determina o tÃ­tulo do link
	 * @param string $title
	 */
	public function setTitle($title){
		$this->title = $title;
	}
	
	/**
	 * Determina que nÃ£o haverÃ¡ uma tooltip
	 */
	public function setNoToolTip(){
		$this->tooltip = FALSE;
		unset($this->class[0]);
	}
	
	/**
	 * Determina a mensagem a ser exibida na tooltip
	 * @param string $msg
	 */
	public function setTooltipMsg($msg){
		$this->tooltipMsg = $msg;
	}
	
	/**
	 * Determina o nÃ­vel de permissÃ£o do link
	 * @param string $permitionLevel
	 */
	public function setPermition($permitionLevel){
		$this->permition = HelperAuth::getPermitionByType($permitionLevel);
	}
	
	/**
	 * Nega a executabilidade do link
	 */
	public function setNoExecutable(){
		$this->executable = FALSE;
	}
	
	/**
	 * Nega a visibilidade do link
	 */
	public function setNoVisible(){
		$this->visible = FALSE;
	}
	
	/**
	 * Recupera o nome do controller
	 * @return array|string
	 */
	public static function getControllerAlias($controller=NULL){
		$controllers = array(
			'trb'=>'trabalho',
			'trb_avl'=>'avaliaÃ§Ã£o do trabalho',
			'trb_capa'=>'capa do trabalho',
			'trb_file'=>'arquivo',
			'trb_res'=>'texto de apresentaÃ§Ã£o do trabalho',
			'trb_rep'=>'link dorepositÃ³rio institucional  para o trabalho',
			'trb_usr'=>'usuÃ¡rio vinculado ao trabalho',
			'usr'=>'usuÃ¡rio do sistema',
			'acs'=>"acesso do usuÃ¡rio",
			'area'=>"Ã¡rea de conhecimento do usuÃ¡rio",
			'bnc'=>'banca',
			'bnc_file'=>"arquivo de consideraÃ§Ãµes sobre a banca",
			'bnc_usr'=>"usuÃ¡rio membro da banca",
			'crono'=>"item do cronograma do semestre",
			'tema'=>'tema',
		);
		
		if(is_null($controller))
			return $controllers;
		else 
			return (key_exists($controller, $controllers) ? $controllers[$controller] : $controller);
	}
	
	/**
	 * Recupera o nome da action
	 * @param string $action
	 * @return string[]|string
	 */
	public static function getActionAlias($action=NULL){
		$actions = array(
				'add'=>'Cadastrar',
				'udt'=>'Alterar',
				'del'=>'Excluir',
				'view'=>'Acessar ',
				'certifica'=>'Gerar certificado para',
				'senha'=>'Alterar a senha do',
				'download'=>'Baixar o arquivo',
				'udtStatus'=>'Alterar status do',
				'assUsrs'=>'Cadastrar',
				'addUsrs'=>'Cadastrar',
				'index'=>'Listar'
		);
		
		if(is_null($action))
			return $actions;
			else
				return (key_exists($action, $actions) ? $actions[$action] : $action);
				
	}
	
	/**
	 * Determina o conteÃºdo do link
	 * @param string $content
	 */
	public function setContent($content){		
		if(substr($content, -4)=='.svg')
			$this->content = HelperFile::getSvgIcon(substr($content, 0, -4));
		else 
			$this->content = $content;
	}
	
	/**
	 * Verifica se o usuÃ¡rio logado possui permissÃ£o para vizualizar o link
	 * @return boolean
	 */
	public function getPermition(){
		if(is_null(HelperAuth::getAuth()))
			return empty($this->permition);
		else
			return (in_array(HelperAuth::getAuth(), $this->permition) OR empty($this->permition));
	}
	
	/**
	 * Recupera a url do link
	 * @return string
	 */
	private function getLink(){
		return $this->link;
	}
	
	/**
	 * Recupera o id do link
	 * @return string|NULL
	 */
	private function getId(){
		return (isset($this->id) ? "id=\"{$this->id}\" " : NULL);
	}
	
	/**
	 * Recupera a(s) classe(s) do link
	 * @return string|NULL
	 */
	private function getClass(){
		$class = implode(' ', $this->class);
		
		return (!empty($this->class) ? "class=\"{$class}\" " : NULL);
	}
	
	/**
	 * Caso setado, determina que o link deve abrir em uma nova janela
	 * @return string|NULL
	 */
	private function getNewWindow(){
		return ($this->newWindow ? 'target="_blank" ' : NULL);
	}
	
	/**
	 * Recupera o tÃ­tulo do link
	 * @return string
	 */
	private function getTitle(){
		$this->title = (isset($this->title) ? $this->title : self::getActionAlias($this->action).' '.self::getControllerAlias($this->controller));
		
		return "title=\"{$this->title}\" ";
	}
	
	/**
	 * Recupera o conteÃºdo do link
	 * @return string|NULL
	 */
	private function getContent(){
		return $this->content;
	}
	
	/**
	 * Recupera a mensagem de tooltip
	 * @return NULL|string
	 */
	private function getTooltip(){
		if(!$this->tooltip)
			return NULL;
		else{
			$action = self::getActionAlias($this->action);
			if(isset($this->tooltipMsg))
				$msg = $this->tooltipMsg;
			else 
				$msg = "<b>{$action}</b>{$this->title}";
			return "\n\t<span class=\"ttp_text\">{$msg}</span>";
		}
	}
	
	/**
	 * Verifica a visibilidade do link
	 * @return boolean
	 */
	private function getVisible(){
		return (is_null($this->visible) ? $this->getPermition() : $this->visible);
	}
}

