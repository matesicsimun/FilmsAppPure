<?php

class HTMLCollection
{
    /**
     * Polje cvorova koji su dio kolekcije
     * @var HTMLNode
     */
    private $nodes;

    /**
     * Stvara novu kolekciju i puni je cvorovima ako je barem jedan čvor predan metodi.
     * Pozicija svakog umetnutog cvora odgovara poziciji cvora u predanom polju.
     *
     * @param {array} $nodes polje cvorova koje je potrebno ubaciti u kolekciju
     */
    public function __construct($nodes = array()){
        $this->nodes = array();
        if (count($nodes) > 0){
            foreach ($nodes as $node){
                $this->nodes[] = $node;
            }
        }

    }

    /**
     * Umece novi cvor u kolekciju cvorova. Cvor se umece na kraj polja, tako da njegovo mjesto
     * uvijek odgovara do tada umetnutom broju cvorova.
     *
     * @param {HTMLNode} $node cvor koji je potrebno umetnuti.
     * @return {integer} mjesto unutar kolekcije na koje je cvor umetnut
     */
    public function add(HTMLNode $node) : int{
        if ($node !== null){
            $this->nodes[] = $node;
            return count($this->nodes)-1;
        }

        return -1;
    }

    /**
     * Dohvaca cvor kolekcije s tocno odredjene pozicije.
     *
     * @param {integer} $position pozicija cvora unutar kolekcije
     * koji je potrebno dohvatiti
     * @return {HTMLNode} cvor s trazene pozicije
     */
    public function get(int $position) : HTMLNode{
        if (array_key_exists($position, $this->nodes)){
            return $this->nodes[$position];
        }
        return null;
    }

    /**
     * Vraca sve elemente kolekcije
     *
     * @return {array} elementi kolekcije
     */
    public function get_all() : array{
        return $this->nodes;
    }

    /**
     * Vraca informaciju o velicini kolekcije
     *
     * @return {int} broj elemenata kolekcije
     */
    public function size() : int{
        return count($this->nodes);
    }

    /**
     * Brise element s tocno odredjene pozicije u kolekciji
     * @param {integer} $position pozicija elementa u kolekciji
     */
    public function delete(int $position){
        if (array_key_exists($position, $this->nodes)){
            unset($this->nodes[$position]);
        }
    }

    public function get_html_collection(){

        $html = '';
        foreach ($this->nodes as $node){
            $html .= $node->get_html();
        }

        return $html;
    }
}