<?php

namespace view;


class AlphabetView extends AbstractView {
    
    private $letters;
    
    public function __construct($letters) {

        $this->letters = $letters;
    }

    public function generateHTML() {
        //show all the letters of the alphabet that are actually links
        //the links point to the address "/Films/index.php?letter=A"
        $neki = new \HTMLTableElement();
        $alphabet_div = new \HTMLDivElement();
        $alphabet_div->add_attribute(new \HTMLAttribute("style", "text-align:center"));

        $upper_bound = new \HTMLHrElement();

        $alphabet_div->add_child($upper_bound);



        foreach($this->letters as $letter){
            $element = new \HTMLAElement();
            $element->add_child(new \HTMLTextNode("|".$letter."|"));
            $element->add_attribute(new \HTMLAttribute("href", "index.php?letter=$letter"));

            $alphabet_div->add_child($element);
        }
        $lower_bound = new \HTMLHrElement();

        $alphabet_div->add_child($lower_bound);

        echo $alphabet_div->get_html();
    }

}
