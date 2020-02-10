<?php
/**
 * @version 19/02/2019 11:55:50
 * @author jose_helio@gmail.com
 *
 */

final class Form_Valor extends Form_Class{

    protected function setFormId(){
        $this->id = 'Form_Valor';
    }
    
    protected function setFormFields(){
        $model = new Model_Cat();
        
        $this->fields = array(
            new Input_Number_Field('valor', 'Valor'),
            //new Input_Field('valor', 'valor'),
            new Input_Date_Field('data', 'Data'),
            new Select_Field('categoria_id', $model->readList('nome', NULL, 'Escolha'), 'Categoria'),
            new Input_Radio_Field('alerta', array(1=>'Sim', 0=>'Não'), 'Crédito'),
            new Input_Submit_Field('enviar'),
        );
        
        $this->fields[0]->setAutofocus();
        $this->fields[0]->setStep(.01);
        $this->fields[1]->setValue(date('Y-m-d'));
        $this->fields[3]->setValue(0);
        $this->fields[3]->setFieldClass('alerta');
    }
}