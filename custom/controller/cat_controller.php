<?php
/**
 * @version 19/02/2019 11:57:11
 * @author jose_helio@gmail.com
 *
 */
include_once PATH_FORM.'Form_Cat.php';

final class cat extends Controller_Class{
    
    public function __construct(){
        parent::__construct();
        if(HelperNavigation::getAction()=='udt')
            $this->item = $this->_model->readOne("tbl.id={$this->id}");
    }
    
    public function main(){
        HelperAuth::auth($this->permitions[HelperNavigation::getAction()]);
        $view = array();
        $soma = 0;
                
        //Listagem
        $view['list'] = $this->_model->read("super_id=0", 'nome');
        foreach ($view['list'] as $i=>$l):
            $view['list'][$i]['subs'] = $this->_model->read("super_id={$l['id']}", 'nome');
            $soma += $l['limite'];
        endforeach;
        $view['soma'] = $soma;
        
        //Botões
        $view['link']['add'] = new Helper_Link(HelperNavigation::getController(), 'categoria', 'add');
        $view['link']['add']->setIsBotao();
        $view['link']['add']->setClass_Button('add');
        
        $id = array();
        foreach ($view['list'] as $i=>$l):
            if(empty($l['subs']))
                $id[] = $l['id'];
            else{
                $id[] = $l['id'];
                foreach ($l['subs'] as $s)
                    $id[] = $s['id'];
            }
        endforeach;
        
        foreach ($id as $i):
            $view['link']['udt'][$i] = new Helper_Link(HelperNavigation::getController(), 'categoria', 'udt', array('id'=>$i));
            $view['link']['udt'][$i]->setIsBotao();
            $view['link']['udt'][$i]->setClass_Button('udt');
        endforeach;
            
        HelperView::setViewData($view);
    }
    
    /**
     * {@inheritDoc}
     * @see Controller_Class::finish()
     */
    protected function finish(){
        $this->item = $this->_model->readOne("id={$this->id}");
        if(in_array(HelperNavigation::getAction(), array('add', 'udt')) AND $this->item['super_id']!=0){
            $super_cat = $this->_model->readOne("id={$this->item['super_id']}");
            $limite = 0;
            foreach ($this->_model->read("super_id={$this->item['super_id']}") as $cat)  
                $limite+= $cat['limite'];
            $values = array(
                'nome'=>$super_cat['nome'],
                'limite'=>$limite,
                'super_id'=>$super_cat['super_id']
            );
            $this->_model->update($values, "id={$this->item['super_id']}");
        }
        parent::finish();
    }
    
    
    /**
     * {@inheritDoc}
     * @see Controller_Class::setModel()
     */
    protected function setModel(){
        $this->_model = new Model_Cat();
    }

    
    /**
     * {@inheritDoc}
     * @see Controller_Class::setForm()
     */
    protected function setForm(){
        $this->_form = new Form_Cat();
    }

    //NEW_METHOD
        
        
    
}