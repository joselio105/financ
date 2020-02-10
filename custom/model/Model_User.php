<?php
/**
 * @version 19/02/2019 11:50:08
 * @author jose_helio@gmail.com
 *
 */

final class Model_User extends Model_Class{
    
    protected function setTableName(){
        $this->_table = 'fnc_usr';
    }

    protected function setFields(){
        $this->_fields = array(
            new FieldTable('nome'),
            new FieldTable('email', TRUE, FALSE, TRUE),
            new FieldTable('senha'),
        );
    }
}