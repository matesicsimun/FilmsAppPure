<?php

namespace view;

class FilmTableView extends AbstractView {

    private $collection;

    public function __construct($collection) {
        $this->collection = $collection;
    }

    public function generateHTML() {
        $div = new \HTMLDivElement();


        $table = new \HTMLTableElement();
        $table->add_attribute(new \HTMLAttribute("border","1"));
        $table->add_attribute(new \HTMLAttribute("width","1500"));
        $table->add_attribute(new \HTMLAttribute("id", "film_table"));

        $header_row = new \HTMLRowElement();
        $header_c1 = new \HTMLCellElement("th");
        $header_c2 = new \HTMLCellElement("th");
        $header_c3 = new \HTMLCellElement("th");
        $header_c4 = new \HTMLCellElement("th");
        $header_c5 = new \HTMLCellElement("th");

        $header_c1->add_child(new \HTMLTextNode("Headline"));
        $header_c2->add_child(new \HTMLTextNode("Title"));
        $header_c3->add_child(new \HTMLTextNode("Year"));
        $header_c4->add_child(new \HTMLTextNode("Duration"));
        $header_c5->add_child(new \HTMLTextNode("Action"));

        $header_c2->add_attribute(new \HTMLAttribute("onclick", "sortTable(1)"));
        $header_c3->add_attribute(new \HTMLAttribute("onclick", "sortTable(2)"));
        $header_c4->add_attribute(new \HTMLAttribute("onclick", "sortTable(3)"));

        $header_row->add_children(new \HTMLCollection([$header_c1, $header_c2, $header_c3, $header_c4, $header_c5]));


        $table->add_child($header_row);

        foreach($this->collection as $film){
            $film_id = $film->getId();
            $row = new \HTMLRowElement();

            $headline = new \HTMLCellElement();
            $headline_img = new \HTMLImgElement();
            $headline_img->add_attribute(new \HTMLAttribute("src","index.php?action=get&id=".$film_id));
            $headline_img->add_attribute(new \HTMLAttribute("height","150"));
            $headline_img->add_attribute(new \HTMLAttribute("width","100"));
            $headline->add_child($headline_img);

            $title = new \HTMLCellElement();
            $title->add_child(new \HTMLTextNode(__($film->getTitle())));
            $title->add_attribute(new \HTMLAttribute("align","center"));

            $year = new \HTMLCellElement();
            $year->add_child(new \HTMLTextNode($film->getYear()));
            $year->add_attribute(new \HTMLAttribute("align","center"));

            $duration = new \HTMLCellElement();
            $duration->add_child(new \HTMLTextNode($film->getDuration()." min"));
            $duration->add_attribute(new \HTMLAttribute("align","center"));

            $action = new \HTMLCellElement();
            $delete = new \HTMLAElement();
            $delete->add_attribute(new \HTMLAttribute("href","index.php?action=delete&id=".$film_id));
            $delete->add_child(new \HTMLTextNode("Delete"));
            $action->add_child($delete);
            $action->add_attribute(new \HTMLAttribute("align","center"));

            $row->add_children(new \HTMLCollection([$headline, $title, $year, $duration, $action]));

            $table->add_child($row);
        }

        $script_string = "function sortTable(n) {
                          var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
                          table = document.getElementById(\"film_table\");
                          switching = true;
                          dir = \"asc\";
                          while (switching) {
                            switching = false;
                            rows = table.rows;
                            for (i = 1; i < (rows.length - 1); i++) {
                              shouldSwitch = false;
                              x = rows[i].getElementsByTagName(\"TD\")[n];
                              y = rows[i + 1].getElementsByTagName(\"TD\")[n];
                              if (dir == \"asc\") {
                                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                  shouldSwitch = true;
                                  break;
                                }
                              } else if (dir == \"desc\") {
                                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                  shouldSwitch = true;
                                  break;
                                }
                              }
                            }
                            if (shouldSwitch) {
                              rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                              switching = true;
                              switchcount ++;
                            } else {
                              if (switchcount == 0 && dir == \"asc\") {
                                dir = \"desc\";
                                switching = true;
                              }
                            }
                          }
                        }";

        $script_element = new \HTMLScriptElement();
        $script_element->add_child(new \HTMLTextNode($script_string));

        $div->add_child($table);
        $div->add_child($script_element);

        echo $div->get_html();
    }




}
