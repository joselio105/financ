<?php
/**
 * @version 19/02/2019 11:56:09
 * @author jose_helio@gmail.com
 *
 */

final class Model_Cat extends Model_Class{
    
    protected function setTableName(){
        $this->_table = 'fnc_cat';
    }

    protected function setFields(){
        $this->_fields = array(
            new FieldTable('nome', TRUE, FALSE, TRUE),
            new FieldTable('limite', TRUE, TRUE),
            new FieldTable('super_id', TRUE, TRUE)
        );
    }
}