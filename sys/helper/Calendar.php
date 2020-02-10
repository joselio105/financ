<?php

class Calendar{
    
    private $action;
    private $exercice;
    private $buttons;
    
    public function __construct($exercice, $action='view'){
        $this->exercice = date('Y-m-d', strtotime($exercice));
        $this->action = $action;
        $this->setButtons();
    }
    
    public function __toString(){
        $string = "";
        
        $string.= "\n<div id=\"calendary\">";
        $string.= "\n\t<div id=\"month\">";
        $string.="\n\t\t{$this->buttons['previous']}";
        $string.="\n\t\t<span>{$this->getMonth()}</span>";
        $string.="\n\t\t{$this->buttons['next']}";
        $string.="\n\t</div>";
        $string.=$this->getWeeks();
        $string.="\n</div>";
        
        return $string;
    }
    
    private function getMonth(){
        return HelperData::mesText(date('n', strtotime($this->exercice))).'/'.date('Y', strtotime($this->exercice));
    }
    
    private function getWeeks(){
        $dayWeek = array( 'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');
        
        $string = "\n\t\t<table>";
        $string.= "\n\t\t\t<tr>";
        foreach ($dayWeek as $day):
            if(!is_null($day))
                $string.= "\n\t\t\t\t<th>{$day}</th>";
        endforeach;
        foreach ($this->getDays() as $dayWeeks):
            $string.= "\n\t\t\t<tr>";
            //foreach ($dayWeeks as $j=>$day):
                foreach ($dayWeek as $i=>$weekDay):
                    if(key_exists($i, $dayWeeks))
                        $string.= "\n\t\t\t\t<td>{$dayWeeks[$i]}</td>";
                    else
                        $string.= "\n\t\t\t\t<td></td>";
                endforeach;
            //endforeach;
            $string.= "\n\t\t\t</tr>";
        endforeach;
        $string.= "\n\t\t\t</tr>";
        $string.= "\n\t\t</table>";
        return $string;
    }
    
    private function getDays(){
        $days = array();
        
        for ($d=1; $d<=date('t', strtotime($this->exercice)); $d++):
            $timestamp = strtotime(date("Y-m-{$d}", strtotime($this->exercice)));
            $week = date('W', $timestamp);
            $week = (date('w', $timestamp)==0 ? str_pad($week+1, 2, 0, STR_PAD_LEFT) : $week);
            
            $days[$week][date('w', $timestamp)] = new Linker($this->action, $d, array('mes'=>date('Y-m-d', $timestamp)));
            $days[$week][date('w', $timestamp)]->setNoToolTip();
        endfor;
        
        return $days;        
    }
    
    private function setButtons(){
        $params = HelperNavigation::getParams();
        $params['mes'] = date('Y-m', strtotime($this->exercice."-1 month"));
        $this->buttons['previous'] = new Linker(HelperNavigation::getAction(), '&#8678;', $params);
        $this->buttons['previous']->setTooltipMsg("Mês Anterior");
        
        $params['mes'] = date('Y-m', strtotime($this->exercice."+1 month"));
        $this->buttons['next'] = new Linker(HelperNavigation::getAction(), '&#8680;', $params);
        $this->buttons['next']->setTooltipMsg("Mês Seguinte");
    }
    
}