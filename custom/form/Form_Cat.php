<?php
/**
 * @version 19/02/2019 11:55:39
 * @author jose_helio@gmail.com
 *
 */

final class Form_Cat extends Form_Class{

    protected function setFormId(){
        $this->id = 'Form_Cat';
    }
    
    protected function setFormFields(){
        $model = new Model_Cat();
        
        $this->fields = array(
            new Input_Field('nome', 'categoria'),
            new Input_Number_Field('limite', 'limite'),
            new Select_Field('super_id', $model->readList('nome', 'super_id=0', 'Nenhuma'), 'Categoria Mãe'),
            new Input_Submit_Field('enviar'),
        );
        
        $this->fields[0]->setAutofocus();
        $this->fields[1]->setValue(1.00);
        $this->fields[1]->setMin(1);
        $this->fields[1]->setStep(0.01);
        $this->fields[2]->setNoRequired();
    }
}