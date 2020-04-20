<?php

namespace view;

class FormView extends AbstractView {

    private $genres;

    public function __construct(array $genres)
    {
        $this->genres = $genres;
    }

    public function generateHTML() {

        $html_collection = new \HTMLCollection();

        $hr = new \HTMLHrElement();
        $html_collection->add($hr);

        $form = new \HTMLFormElement();
        $form->add_attribute(new \HTMLAttribute("class", "form-inline"));
        $form->add_attribute(new \HTMLAttribute("action","index.php?action=add"));
        $form->add_attribute(new \HTMLAttribute("method","post"));
        $form->add_attribute(new \HTMLAttribute("enctype","multipart/form-data"));

        //create title div
        $input_div = $this->create_input_div("title", "text");

        //create genre div
        $genre_label = new \HTMLLabelElement();
        $genre_label->add_attribute(new \HTMLAttribute("for", "genre"));
        $genre_label->add_child(new \HTMLTextNode("Genre: "));

        $genre_select = new \HTMLSelectElement();
        $genre_select->add_attribute(new \HTMLAttribute("id","genre"));
        $genre_select->add_attribute(new \HTMLAttribute("name","genre"));
        $genre_select->add_attribute(new \HTMLAttribute("required","true"));

        foreach($this->genres as $genre){
            $option = new \HTMLOptionElement();
            $option->add_attribute(new \HTMLAttribute("value", $genre));
            $option->add_child(new \HTMLTextNode($genre));

            $genre_select->add_child($option);
        }

        //create year select
        $year_label = new \HTMLLabelElement();
        $year_label->add_attribute(new \HTMLAttribute("for","year"));
        $year_label->add_child(new \HTMLTextNode("Year: "));

        $year_select = new \HTMLSelectElement();
        $year_select->add_attribute(new \HTMLAttribute("id", "year"));
        $year_select->add_attribute(new \HTMLAttribute("name", "year"));
        $year_select->add_attribute(new \HTMLAttribute("required","true"));

        for($i = 1900; $i < date('Y'); $i++){
            $option = new \HTMLOptionElement();
            $option->add_attribute(new \HTMLAttribute("value",$i));
            $option->add_child(new \HTMLTextNode($i));

            $year_select->add_child($option);
        }

        //create duration div
        $duration_div = $this->create_input_div("duration", "number");

        //create headline
        $headline_input = new \HTMLInputElement();
        $headline_input->add_attribute(new \HTMLAttribute("type", "file"));
        $headline_input->add_attribute(new \HTMLAttribute("name", "headline"));
        $headline_input->add_attribute(new \HTMLAttribute("id", "headline"));
        $headline_input->add_attribute(new \HTMLAttribute("required","true"));
        $headline_input->add_attribute(new \HTMLAttribute("accept","image/*"));

        $headline_label = new \HTMLLabelElement();
        $headline_label->add_attribute(new \HTMLAttribute("for","headline"));
        $headline_label->add_child(new \HTMLTextNode("Headline: "));


        $submit = new \HTMLInputElement();
        $submit->add_attribute(new \HTMLAttribute("type","submit"));
        $submit->add_attribute(new \HTMLAttribute("value","submit"));

        $form->add_child($input_div);
        $form->add_child($genre_label);
        $form->add_child($genre_select);
        $form->add_child($year_label);
        $form->add_child($year_select);
        $form->add_child($duration_div);
        $form->add_child($headline_label);
        $form->add_child($headline_input);
        $form->add_child($submit);

        $html_collection->add($form);
        $hr = new \HTMLHrElement();
        $html_collection->add($hr);

        echo $html_collection->get_html_collection();
    }

    private function create_input_div(string $name, string $type){
        $input_div = new \HTMLDivElement();

        $input_label = new \HTMLLabelElement();
        $input_label->add_attribute(new \HTMLAttribute("for", $name));
        $input_label->add_child(new \HTMLTextNode(ucfirst($name). ": "));

        $input = new \HTMLInputElement();
        $input->add_attribute(new \HTMLAttribute("type", $type));
        $input->add_attribute(new \HTMLAttribute("name",$name));
        $input->add_attribute(new \HTMLAttribute("id",$name));
        $input->add_attribute(new \HTMLAttribute("required","true"));

        if($type === "number"){
            $input->add_attribute(new \HTMLAttribute("min",10));
            $input->add_attribute(new \HTMLAttribute("max",300));
        }

        $input_div->add_child($input_label);
        $input_div->add_child($input);

        return $input_div;
    }

}
