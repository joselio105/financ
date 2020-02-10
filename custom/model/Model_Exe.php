<?php
/**
 * @version 04/04/2019 12:57:13
 * @author jose_helio@gmail.com
 *
 */

final class Model_Exe extends Model_Class{
    
    protected function setTableName(){
        $this->_table = 'fnc_exe';
    }

    protected function setFields(){
        $this->_fields = array(
            new FieldTable('inicio', TRUE, FALSE, TRUE),
            new FieldTable('exercicio', TRUE, FALSE, TRUE)
        );
    }
    
    /**
     * Informa as datas de início de exercício anteriores e seguintes
     * @param array $exercicio
     * @return string[]
     */
    public function getBegins(array $exercicio){
        $before = date('Y-m-d', strtotime("{$exercicio['inicio']} -1 month"));
        $after = date('Y-m-d', strtotime("{$exercicio['inicio']} +1 month"));
        
        $prev = $this->readOne("inicio={$before}");
        $next = $this->readOne("inicio={$after}");
        
        $res = array(
            'actual'=>$exercicio['inicio'],
            'prev'=>(!is_null($prev) ? $prev['inicio'] : $before),
            'next'=>(!is_null($next) ? $next['inicio'] : $after)
        );
        
        return $res;
    }
    
    /**
     * Identifica o exercício atual
     * @return number
     */
    public function getAtual(){
        $today = date('Y-m-d');
        $id = count($this->read());
        
        foreach ($this->read() as $exe):
            if($today>=$exe['inicio'])
                $id = $exe['id'];
        endforeach;
        
        return $id;        
    }
}