<?php

namespace view;

require_once "lib/HTMLLibrary/classes.php";

class HeaderView extends AbstractView {

    private $title;
    private $isBack;

    public function __construct($title, $isBack = false) {
        $this->title = $title;
        $this->isBack = $isBack;
    }

    public function generateHTML() {
        $table = new \HTMLTableElement();
        $table->add_attribute(new \HTMLAttribute("style","margin: 0px;"));
        $table->add_attribute(new \HTMLAttribute("width","2000"));

        $row = new \HTMLRowElement();
        $cell_1 = new \HTMLCellElement();
        $cell_2 = new \HTMLCellElement();
        $cell_3 = new \HTMLCellElement();


        //create link for back
        $index_link = new \HTMLAElement();
        $index_link->add_attribute(new \HTMLAttribute("href","index.php"));

        $index_link_img = new \HTMLImgElement();
        $index_link_img->add_attribute(new \HTMLAttribute("src","resources/movies.png"));
        $index_link->add_child($index_link_img);

        $cell_1->add_child($index_link);

        //create movies string
        $text = new \HTMLTextNode($this->title);

        $cell_2->add_child($text);

        if ($this->isBack){
            $back_link = new \HTMLAElement();
            $back_link->add_attribute(new \HTMLAttribute("href", "index.php"));

            $back_link_img = new \HTMLImgElement();
            $back_link_img->add_attribute(new \HTMLAttribute("src","resources/back.png"));
            $back_link->add_child($back_link_img);

            $cell_3->add_child($back_link);
        } else {
            $add_link = new \HTMLAElement();
            $add_link->add_attribute(new \HTMLAttribute("href","index.php?action=add"));

            $add_link_img = new \HTMLImgElement();
            $add_link_img->add_attribute(new \HTMLAttribute("src","resources/add.png"));

            $add_link->add_child($add_link_img);

            $cell_3->add_child($add_link);
        }

        $row->add_children(new \HTMLCollection([$cell_1, $cell_2, $cell_3]));
        $table->add_child($row);

        echo $table->get_html();
    }

}
