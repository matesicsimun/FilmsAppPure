<?php
require_once "classes.php";

abstract class HTMLElement extends HTMLNode
{
    /**
     * Polje atributa koji pripadaju elementu
     * @var HTMLAttribute
     */
    protected $attributes;

    /**
     * Djeca HTML elementa
     * @var HTMLCollection
     */
    protected $children;

    /**
     * Zastavica koja oznacava ima li otvarajuci tag i pripadajuci zatvarajuci tag.
     */
    protected $closed;

    /**
     * Naziv HTML elementa.
     */
    protected $name;

    /**
     * Stvara novi element zadanog naziva uz posvecivanje
     * paznje na otvarajuce i zatvarajuce tagove.
     * @param {string} $name
     * @param bool $closed
     */
    public function __construct(string $name, bool $closed = true){
        $this->closed = $closed;
        $this->name = $name;

        $this->attributes = array();
        $this->children = new HTMLCollection();
    }

    /**
     * Elementu dodaje novo dijete.
     * @param {HTMLNode} $node novo dijete
     * @return {integer} pozicija dodanog dijeteta unutar polja djece, -1 ako je predana null vrijednost umjesto čvora
     */
    public function add_child(HTMLNode $node) : int{
        return $this->children->add($node);
    }

    /**
     * Elementu dodaje cijelu kolekciju elemenata koji ce biti njegova djeca.
     * @param  {HTMLColletion} $collection
     */
    public function add_children(HTMLCollection $collection){

        foreach ($collection->get_all() as $element){
            $this->add_child($element);
        }
    }

    /**
     * Vraca dijete koje se nalazi na zadanoj poziciji.
     * @param $position
     * @return {HTMLNode} dijete na zadanoj poziciji.
     */
    public function get_child(int $position) : HTMLNode{
        return $this->children->get($position);
    }

    /**
     * Vraca trenutni broj djece elementa.
     * @return {integer} broj djece
     */
    public function get_children_number() : int{
        return $this->children->size();
    }

    /**
     * Uklanjanje dijete koje se nalazi na poziciji odredjenoj parametrom.
     * @param {integer} $position pozicija na kojoj se dijete nalazi
     */
    public function remove_child(int $position){
        $this->children->delete($position);
    }

    /**
     * Obavlja dodavanje novog atributa.
     * @param {HTMLAttribute} $attribute
     */
    public function add_attribute(HTMLAttribute $attribute){
        if ($attribute !== null){
            $this->attributes[$attribute->get_name()] = $attribute;
        }
    }

    /**
     * Iz polja atributa uklanja atribut zadanog imena.
     * @param {string} $attribute ime atributa kojeg treba ukloniti
     */
    public function remove_attribute(string $attribute){
        if (key_exists($attribute, $this->attributes)){
            unset($this->attributes[$attribute]);
        }
    }

    /**
     * Vraca naziv elementa.
     * @return {string} naziv elementa
     */
    public function get_name() : string{
        return $this->name;
    }

    /**
     * Pretvara element u znakovni niz.
     */
    public function __toString() : string{
        return $this->get_html();
    }

    public function get_head_tag() : string{
        /*
        * Create opening tag
        */
        $head_tag = '<'.$this->get_name();
        $attrString = '';
        foreach ($this->attributes as $attribute){
            $attrString .= ' '. $attribute->__toString() . '';
        }
        $head_tag .= $attrString . '>';

        return $head_tag;
    }

    public function get_tail_tag() : string{
        if ($this->closed){
            return "</".$this->get_name().">";
        }
        return '';
    }

    public function get_html() : string{
        //add opening tag
        $html = $this->get_head_tag();

        //add children to html
        $html .= $this->children->get_html_collection();

        //add closing tag - appends nothing if $closed === false
        $html .= $this->get_tail_tag();

        return $html;
    }
}