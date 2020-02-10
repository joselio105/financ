<?php
include_once PATH_SYSTEM_SRC.'model/crud/Sql_Class.php';

class Create_Class extends Sql_Class{
    
    /**
     * {@inheritDoc}
     * @see Sql_Class::setSql()
     */
    public function setSql(){
        $this->sql = "INSERT INTO {$this->getTable()}({$this->getFields()}) VALUES({$this->getValues()})";
    }
    
    /**
     * {@inheritDoc}
     * @see Sql_Class::getFields()
     */
    protected function getFields(){
       return implode(', ', array_keys(parent::getFields()));
    }
    
    /**
     * Retorna o array de valores
     * @return string
     */
    private function getValues(){
        $res = array();
        foreach ($this->_fields as $field)
            $res[] = $field->getValue();
        return '"'.implode('", "', $res).'"';
    }

    
}