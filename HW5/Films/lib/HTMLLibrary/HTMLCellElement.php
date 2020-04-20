<?php

class HTMLCellElement extends HTMLElement
{

    public function __construct(string $type = "td")
    {
        parent::__construct($type, true);
    }


    public function add_text(string $text){
        $textNode = new HTMLTextNode($text);
        $this->add_text_node($textNode);
    }

    public function add_text_node(HTMLTextNode $textNode){
        $this->add_child($textNode);
    }
}