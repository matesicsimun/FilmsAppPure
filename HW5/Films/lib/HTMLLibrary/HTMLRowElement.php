<?php

class HTMLRowElement extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("tr", true);
    }

    public function add_cell_text(string $text){
        $cell = new HTMLCellElement();
        $textNode = new HTMLTextNode($text);
        $cell->add_child($textNode);

        $this->add_cell($cell);
    }

    public function add_cell_text_node(HTMLTextNode $textNode){
        $cell = new HTMLCellElement();
        $cell->add_child($textNode);

        $this->add_cell($cell);
    }

    public function add_cell(HTMLCellElement $cell){
        $this->add_child($cell);
    }

    public function add_cells(array $cells){
        foreach ($cells as $cell){
            $this->add_cell($cell);
        }
    }
}