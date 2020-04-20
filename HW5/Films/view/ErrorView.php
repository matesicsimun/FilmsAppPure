<?php

namespace view;

class ErrorView extends AbstractView {

    private $message;

    public function __construct($message) {
        $this->message = $message;
    }

    public function generateHTML() {
        $html = new \HTMLDivElement();
        $html->add_attribute(new \HTMLAttribute("style","text-align:center"));

        $error_img = new \HTMLImgElement();
        $error_img->add_attribute(new \HTMLAttribute("src", "resources/error.png"));
        $error_img->add_attribute(new \HTMLAttribute("align","center"));

        $text = new \HTMLPelement();
        $text->add_attribute(new \HTMLAttribute("align","center"));
        $text->add_child(new \HTMLTextNode($this->message));


        $html->add_child($error_img);
        $html->add_child($text);

        echo $html->get_html();
    }

}
