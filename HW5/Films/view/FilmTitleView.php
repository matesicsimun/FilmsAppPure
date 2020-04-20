<?php

namespace view;

class FilmTitleView extends AbstractView {

    /**
     *
     * @var \model\Film[]
     */
    private $collection;

    public function __construct($collection) {
        $this->collection = $collection;
    }

    public function generateHTML() {
        $html_collection = new \HTMLCollection();
        //foreach movie
        foreach($this->collection as $film){

            $film_id = $film->getId();
            $img_div = new \HTMLDivElement();
            $img_div->add_attribute(new \HTMLAttribute("align","center"));
            $img_element = new \HTMLImgElement();
            $img_element->add_attribute(new \HTMLAttribute("src","index.php?action=get&id=".$film_id));
            $img_element->add_attribute(new \HTMLAttribute("width","200"));
            $img_element->add_attribute(new \HTMLAttribute("height","300"));
            $img_div->add_child($img_element);

            $title_element = new \HTMLDivElement();
            $title_element->add_attribute(new \HTMLAttribute("align","center"));
            $film_title = new \HTMLTextNode(__($film->getTitle()));
            $title_element->add_child($film_title);

            $duration_element = new \HTMLDivElement();
            $duration_element->add_attribute(new \HTMLAttribute("align","center"));
            $film_duration = new \HTMLTextNode($film->getDuration() . " min");
            $duration_element->add_child($film_duration);

            $html_collection->add(new \HTMLBrElement());
            $html_collection->add($img_div);
            $html_collection->add($title_element);
            $html_collection->add($duration_element);
            $html_collection->add(new \HTMLBrElement());
        }

        echo $html_collection->get_html_collection();



    }


}
