<?php
/**
 * @version 04/04/2019 12:57:24
 * @author jose_helio@gmail.com
 *
 */

final class Form_Exe extends Form_Class{

    protected function setFormId(){
        $this->id = 'Form_Exe';
    }
    
    protected function setFormFields(){
        $this->fields = array(
            new Input_Date_Field('inicio', 'Início'),
            new Input_Submit_Field('enviar'),
        );
        $this->fields[0]->setAutofocus();
    }
}