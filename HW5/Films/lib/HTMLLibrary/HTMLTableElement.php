<?php

class HTMLTableElement extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("table", true);
    }

    public function add_empty_row(){
        $row = new HTMLRowElement();
        $this->add_child($row);
    }

    public function add_existing_row(HTMLRowElement $row){
        $this->add_child($row);
    }

}