<?php

class HTMLTitleElement extends HTMLElement
{
    public function __construct(...$titleText)
    {
        parent::__construct("title", true);

        if (func_num_args() === 1){
            $html_text_node = new HTMLTextNode(func_get_arg(0));
            $this->add_child($html_text_node);
        }
    }

}