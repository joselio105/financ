<?php
/**
 * @version 26/11/2018 10:22:05
 * @author jose_helio@gmail.com
 *
 */

final class index{
    
    public function main(){
        HelperNavigation::redirect('auth');
    }
    
    
    public function sobre(){
        $view = array();

        HelperView::setViewData($view);
    }

    
    public function contato(){
        $view = array();

        HelperView::setViewData($view);
    }

    //NEW_METHOD
    
        
    
        
    
}