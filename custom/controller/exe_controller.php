<?php
/**
 * @version 04/04/2019 12:57:34
 * @author jose_helio@gmail.com
 *
 */

final class exe extends Controller_Class{
    
    public function main(){
        HelperAuth::auth($this->permitions[HelperNavigation::getAction()]);
        $view = array();
        
        //Listagem
        $view['list'] = $this->_model->read(NULL, 'exercicio', TRUE);
        
        $view['link']['add'] = new Helper_Link(HelperNavigation::getController(), 'exercício', 'add');
        $view['link']['add']->setIsBotao();
        $view['link']['add']->setClass_Button('add');
        
        foreach ($view['list'] as $l):
            $view['link']['udt'][$l['id']] = new Helper_Link(HelperNavigation::getController(), 'exercício', 'udt', array('id'=>$l['id']));
            $view['link']['udt'][$l['id']]->setIsBotao();
            $view['link']['udt'][$l['id']]->setClass_Button('udt');
        endforeach;
        
        HelperView::setViewData($view);
    }
    
    
    protected function getValues(){
        $res = parent::getValues();
        $res['exercicio'] = date('Y-m-01', strtotime($res['inicio']));
        
        return $res; 
    }

    /**
     * {@inheritDoc}
     * @see Controller_Class::setModel()
     */
    protected function setModel(){
        $this->_model = new Model_Exe();
    }

    
    /**
     * {@inheritDoc}
     * @see Controller_Class::setForm()
     */
    protected function setForm(){
        $this->_form = new Form_Exe();
    }

    //NEW_METHOD
        
        
    
}