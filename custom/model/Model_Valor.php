<?php
/**
 * @version 19/02/2019 11:56:22
 * @author jose_helio@gmail.com
 *
 */

final class Model_Valor extends Model_Class{
    
    private $modelCat;
    
    public function __construct(){
        parent::__construct();
        $this->modelCat = new Model_Cat();
        $this->setJoin('fnc_cat', 'tbl.categoria_id=fnc_cat.id', array('nome'=>'categoria', 'limite', 'super_id'));
    }
    
    protected function setTableName(){
        $this->_table = 'fnc_val';
    }

    protected function setFields(){
        $this->_fields = array(
            new FieldTable('valor', TRUE, TRUE),
            new FieldTable('categoria_id', TRUE, TRUE),
            new FieldTable('data'),
            new FieldTable('credito', TRUE, TRUE),
        );
    }
    
    public function getMeses(){
        $meses = array();
        
        foreach ($this->read() as $valor)
            $meses[date('Y-m', strtotime($valor['data']))] = HelperData::mesText(date('n', strtotime($valor['data']))).'/'.date('Y', strtotime($valor['data']));
        krsort($meses, SORT_STRING);
         
        return $meses;
    }    
    
    
    /**
     * {@inheritDoc}
     * @see Model_Class::sum()
     */
    public function sum($fieldToSum, $where = NULL){
        $clauses = explode(' AND ', $where);
        $categoria_id = substr($clauses[0], strlen('categoria_id='));
        
        if($this->checkCategory($categoria_id)){
           unset($clauses[0]);
           $clauses = (!empty($clauses) ? ' AND '.implode(' AND ', $clauses) : NULL);
           $sum = 0;
           foreach ($this->modelCat->read("super_id={$categoria_id}") as $subCat) 
               $sum+= parent::sum($fieldToSum, "categoria_id={$subCat['id']}{$clauses}");
        }else
            $sum = parent::sum($fieldToSum, $where);
        
        return $sum;
    }
    
    /**
     * Verifica se a categoria tem subcategorias
     * @param int $id
     * @return boolean
     */
    private function checkCategory($id){
        $res = FALSE;
        
        foreach ($this->modelCat->read() as $cat):
            if($cat['super_id']==$id)
                $res = TRUE;
        endforeach;
        
        
        return $res;
    }

}