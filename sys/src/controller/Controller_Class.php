<?php
include_once PATH_DEFAULT.'form/Form_Delete.php';

abstract class Controller_Class{
    
    protected $_model;
    protected $_form;
    protected $id;
    protected $item;
    protected $permitions;
    private $msgDel;
        
    /**
     * Controla as interações entre usuário e sistema
     */
    public function __construct(){
        $this->setPermitions();
        $this->loadFiles();
        $this->setModel();
        $this->setForm();
        $this->setMsgDel();
        
        if(in_array(HelperNavigation::getAction(), array('add', 'udt')))
            $this->_form->setAction(HelperNavigation::getController(), HelperNavigation::getAction(), HelperNavigation::getParams());
        if(in_array(HelperNavigation::getAction(), array('view', 'del', 'udt')))
            $this->id = HelperNavigation::getParam('id');
    }
    
    public abstract function main();
    
    /**
     * Cadastra um item na tabela do banco de dados
     */
    public function add(){
        HelperAuth::auth($this->getPermition());
        if(key_exists('render', HelperNavigation::getParams()))
            HelperView::setRenderFalse();
        $view = array();
        
        $view['form'] = $this->_form;
        
        if($this->_form->isSubmitedForm()){
            $this->id = $this->_model->create($this->getValues());
            
            $this->finish();
        }
        
        HelperView::setViewData($view);
    }
    
    /**
     * Edita um item na tabela do banco de dados
     */
    public function udt(){
        HelperAuth::auth($this->getPermition());
        if(key_exists('render', HelperNavigation::getParams()))
            HelperView::setRenderFalse();
        $view = array();
        
        $this->item = $this->_model->readOne("tbl.id={$this->id}");
        
        $this->_form->populate($this->item);
        $view['form'] = $this->_form;
        
        if($this->_form->isSubmitedForm()){
            $this->_model->update($this->getValues(), "id={$this->id}");
            $this->finish();
        }            
        
        HelperView::setViewData($view);
    }
    
    /**
     * Exclui um item na tabela do banco de dados
     */
    public function del(){
        HelperAuth::auth($this->getPermition());
        if(key_exists('render', HelperNavigation::getParams()))
            HelperView::setRenderFalse();
        $view = array();
        
        $view['form'] = new Form_Delete($this->getMsgDel());
        $view['form']->setAction(HelperNavigation::getController(), HelperNavigation::getAction(), HelperNavigation::getParams());
        if($view['form']->isSubmitedForm()){
            if($view['form']->readFieldForm('bt_yes')=='Sim'){
                if($this->_model->delete('id='.$this->id))
                    $this->finish();
            }
            if($view['form']->readFieldForm('bt_no')=='Não')
                $this->finish();                
        }
        
        
        HelperView::setViewData($view);
    }
    
    /**
     * Finaliza a ação
     */
    protected function finish(){
        HelperNavigation::redirect(HelperNavigation::getController());
    }
    
    /**
     * Adapata os dados recebidos pelo forumário aos campos do model
     * @return array
     */
    protected function getValues(){
        return $this->_form->readForm();
    }
    
    /**
     * Determina a mensagem a ser exibida no forumulário de exclusão
     * @param string $msg
     */
    protected function setMsgDel($msg='Deseja excluir esse item?'){
        $this->msgDel = $msg;
    }
    
    /**
     * Recupera a mensagem a ser exibida no formulário de exclusão
     * @return string
     */
    protected function getMsgDel(){
        return $this->msgDel;
    }
    
    /**
     * Faz o include de todos os models de formulários
     */
    protected function loadFiles(){
        //include models
        foreach (glob(PATH_MODEL."Model_*.php") as $file)
            include_once $file;
        
        //include forms
        foreach (glob(PATH_FORM."Form_*.php") as $file)
            include_once $file;
    }
    
    /**
     * Define o nível de permissão para cada ação da classe
     */
    protected function setPermitions(){
        $this->permitions = array(
            'main'=>HelperAuth::getPermitionByType(PERMITION_LEVEL_ADMIN),
            'add'=>HelperAuth::getPermitionByType(PERMITION_LEVEL_ADMIN),
            'udt'=>HelperAuth::getPermitionByType(PERMITION_LEVEL_ADMIN),
            'del'=>HelperAuth::getPermitionByType(PERMITION_LEVEL_ADMIN),
        );
    }
    
    /**
     * Retorna o nível de permissão da ação atual
     * @return array|NULL
     */
    protected function getPermition(){
        return $this->permitions[HelperNavigation::getAction()];
    }
    
    /**
     * Determina o model a ser usado
     */
    protected abstract function setModel();
    
    /**
     * Determina o formulário a ser usado
     */
    protected abstract function setForm();
    
}
