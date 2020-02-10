<?php

final class ContentCard{
    
    private $tag;
    private $value;
    private $attrs;
    private $valueField;

    public function __construct($value, $tag='div', array $attrs=NULL){
        $this->value = $value;
        $this->tag = $tag;
        $this->attrs = (!is_null($attrs)?$attrs:array());
    }
    
    public function getTag()
    {
        return $this->tag;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getAttrs()
    {
        return $this->attrs;
    }

    public function getValueField()
    {
        return $this->valueField;
    }

    public function setValueField($valueField)
    {
        $this->valueField = $valueField;
    }

    
    
}

