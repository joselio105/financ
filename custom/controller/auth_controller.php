<?php
/**
 * @version 27/11/2018 10:22:11
 * @author jose_helio@gmail.com
 *
 */
include_once PATH_MODEL.'Model_User.php';
include_once PATH_FORM.'Form_Auth.php';

final class auth{
    
    private $form;
    private $model;
    private $user;
    
    public function __construct(){
        $this->form = new Form_Auth();
        $this->model = new Model_User();
    }
    
    public function main(){
        $view = array();
        
        $view['form'] = $this->form;
        
        if($this->form->isSubmitedForm()){
            $where = "email={$this->form->readFieldForm('email')} AND senha={$this->form->readFieldForm('senha')}";
            if(is_null($this->user = $this->model->readOne($where)))
                HelperView::setAlert("Ususário inválido!<br>E-mail ou senha não conferem!");
            else
                $this->login();            
        }
        
        HelperView::setViewData($view);
    }
    
    
    public function login(){
        HelperAuth::register($this->user);
        HelperNavigation::redirect('valor');
    }

    
    public function logout(){
        HelperAuth::unregister();
    }

    //NEW_METHOD
    
}