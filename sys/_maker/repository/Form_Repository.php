<?php
/**
 * @version DATA_CRIACAO
 * @author jose_helio@gmail.com
 *
 */

final class CLASS_NAME extends Form_Class{

    protected function setFormId(){
        $this->id = 'CLASS_NAME';
    }
    
    protected function setFormFields(){
        $this->fields = array(
            new Input_Submit_Field('enviar'),
        );
    }
}