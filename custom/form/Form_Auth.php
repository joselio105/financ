<?php
/**
 * @version 26/11/2018 10:31:55
 * @author jose_helio@gmail.com
 *
 */

final class Form_Auth extends Form_Class{

    protected function setFormId(){
        $this->id = 'Form_Auth';
    }
    
    protected function setFormFields(){
        $this->fields = array(
            new Input_Field('email', 'E-Mail'),
            new Input_Field('senha', 'Senha'),
            new Input_Submit_Field('enviar'),
        );
        
        $this->fields[0]->setType('email');
        $this->fields[1]->setType('password');
    }
}