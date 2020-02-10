<?php

/**
 * @version 19/02/2019 11:57:21
 * @author jose_helio@gmail.com
 *
 */
include_once PATH_FORM.'Form_Valor.php';
include_once 'custom/helper/Helper_Graphyc.php';

final class valor extends Controller_Class{
    
    private $modelCat;
    private $modelExe;
    private $categoriaId;
    
    public function __construct(){
        parent::__construct();
        $this->modelCat = new Model_Cat();
        $this->modelExe = new Model_Exe();
        if(in_array(HelperNavigation::getAction(), array('udt', 'del', 'view')))
            $this->categoriaId = HelperNavigation::getParam('categoria_id');
    }
    
    public function main(){
        HelperAuth::auth($this->permitions[HelperNavigation::getAction()]);
        $view = array();
        
        //Mês atual
        $view['mesAtual'] = date('Y-m');     
        $view['mes'] = (!is_null(HelperNavigation::getParam('mes')) ? HelperNavigation::getParam('mes') : $view['mesAtual']);
        $view['mesNext'] = date('Y-m', strtotime($view['mes']."+1 month"));   
        
        //Ordenando a tabela
        $view['order'] = (!is_null(HelperNavigation::getParam('order')) ? HelperNavigation::getParam('order') : 'nome');
        if($view['order']=='nome')
            $order = array('orderBy'=>$view['order'], 'order'=>'ASC', 'type'=>SORT_LOCALE_STRING);
        else
            $order = array('orderBy'=>$view['order'], 'order'=>'DESC', 'type'=>SORT_NUMERIC);
       
       //Meses     
       $view['meses'] = $this->_model->getMeses();
        
        //Botão ADD
        $view['link']['add'] = new Helper_Link(HelperNavigation::getController(), 'valor', 'add');
        $view['link']['add']->setIsBotao();
        $view['link']['add']->setClass_Button('add');
            
        //lista das últimas movimentações
        $view['lastVals'] = $this->_model->read("tbl.data <'{$view['mesNext']}'", 'tbl.data', TRUE, 10, 0);
        
        //Tabela de categorias
        $where = " AND tbl.data >= '{$view['mes']}' AND tbl.data <'{$view['mesNext']}'";
        
        //Calendário
        $view['calendary'] = new Calendar($view['mes']);
        
        foreach ($this->modelCat->read() as $cat):
            if($cat['nome']!='Mercado'){
                $view['cat'][$cat['id']] = $cat;
                $view['cat'][$cat['id']]['soma']    = $this->_model->sum('valor', "categoria_id={$cat['id']}{$where}");
                $view['cat'][$cat['id']]['saldo']   = $cat['limite'] - $view['cat'][$cat['id']]['soma'];
                $view['cat'][$cat['id']]['percent'] = ($view['cat'][$cat['id']]['limite']!=0 ? $view['cat'][$cat['id']]['soma'] / $view['cat'][$cat['id']]['limite'] : 0);
                
                $view['cat'][$cat['id']]['link'] = new Helper_Link('valor', $cat['nome'], 'view', array('categoria_id'=>$cat['id']));
            }
        endforeach;
        $view['cat'] = HelperView::orderTable($view['cat'], $order);
        
        $view['total'] = array(
            'limite' => 0,
            'soma' => 0,
            'saldo' => 0,
            'percent' => 0,
        );
        foreach ($view['cat'] as $cat):
            $view['total']['limite']    += $cat['limite'];
            $view['total']['soma']      += $cat['soma'];
            $view['total']['saldo']     += $cat['saldo'];
            
            $view['total']['link']      = new Helper_Link('valor', 'Total', 'view');
        endforeach;
        $view['total']['percent'] = ($view['total']['limite']!=0 ? $view['total']['soma']/$view['total']['limite'] : 0);
        
        HelperView::setViewData($view);
    }
    
    public function view(){
        HelperAuth::auth($this->permitions[HelperNavigation::getAction()]);
        $view = array();
        
        //Meses
        $view['meses'] = $this->_model->getMeses();
        
        //Mês atual
        $view['mesAtual'] = date('Y-m');
        $view['mes'] = (!is_null(HelperNavigation::getParam('mes')) ? HelperNavigation::getParam('mes') : $view['mesAtual']);
        $view['mesNext'] = date('Y-m', strtotime($view['mes']."+1 month"));   
        
        //Lista lançamentos
        $where = "tbl.data LIKE '{$view['mes']}%'";
        if(!is_null($this->categoriaId))
            $where .= " AND categoria_id={$this->categoriaId}";
        $view['lista'] = $this->_model->read($where, 'data', TRUE);
        $view['total'] = $this->_model->sum('valor', $where);
        
        //Nome da categoria
        $view['categoria'] = (!is_null($this->categoriaId) ? $this->modelCat->readOne("tbl.id={$this->categoriaId}") : array('nome'=>'Total'));
        $brk = explode('-', $view['mes']);
        $date = (count($brk)==3 ? "{$brk[2]}/{$brk[1]}/{$brk[0]}" : "{$brk[1]}/{$brk[0]}");
        $view['title'] = "{$view['categoria']['nome']} - {$date}";
        //Lista de categorias
        $view['categorias'] = $this->modelCat->readList('nome', NULL, 'Todas');
        $view['categoria_id'] = $this->categoriaId;
        
        //Botões UDT e DEL
        foreach($view['lista'] as $lanc):
            foreach (array('udt', 'del') as $act):
                $view['link'][$act][$lanc['id']] = new Helper_Link(HelperNavigation::getController(), 'Lançamento', $act, array('id'=>$lanc['id'], 'categoria_id'=>$this->categoriaId));
                $view['link'][$act][$lanc['id']]->setIsBotao();
                $view['link'][$act][$lanc['id']]->setClass_Button($act);
            endforeach;
        endforeach;
        
        HelperView::setViewData($view);
    }
    
    
    /**
     * {@inheritDoc}
     * @see Controller_Class::setModel()
     */
    protected function setModel(){
        $this->_model = new Model_Valor();
    }
    
    /**
     * {@inheritDoc}
     * @see Controller_Class::setForm()
     */
    protected function setForm(){
        $this->_form = new Form_Valor();
    }
    
    /**
     * {@inheritDoc}
     * @see Controller_Class::setPermitions()
     */
    protected function setPermitions(){
        parent::setPermitions();
        $this->permitions['view'] = HelperAuth::getPermitionByType(PERMITION_LEVEL_ADMIN);
    }

    /**
     * {@inheritDoc}
     * @see Controller_Class::getValues()
     */
    protected function getValues(){
        $res = $this->_form->readForm();
        //$res['valor'] = $this->getValorSoma();
        $res['credito'] = $res['alerta'];
        unset($res['alerta']);
        
        return $res;
    }
    
    /**
     * {@inheritDoc}
     * @see Controller_Class::finish()
     */
    protected function finish(){
        if(HelperNavigation::getAction()=='add')
            parent::finish();
        else 
            HelperNavigation::redirect(HelperNavigation::getController(), 'view', array('categoria_id'=>$this->categoriaId));
    }
    
    private function getValorSoma(){
        $val = trim($this->_form->readFieldForm('valor'));
        var_dump($val, number_format($val, 2), $val);die;
        if(!is_numeric($val)){
            $soma = 0;
            $parcelas = explode('+', $val);
            foreach ($parcelas as $p):
                if(is_numeric(trim($p))){
                    $soma += trim($p);
                }
                return 0;
            endforeach;
            return $soma;
        }
        
        return $val;
    }
    
    //NEW_METHOD
    
        
    
}