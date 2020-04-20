<?php

/**
 * Implementacija HTML atributa
 * Class HTMLAttribute
 */
class HTMLAttribute
{

    /**
     * Naziv atributa
     * @var {string}
     */
    private $name;

    /**
     * Vrijednost atributa koja moze biti niz znakova ako se radi o samoj jednoj vrijednosti,
     * odnosno polje ako se radi o više vrijednosti
     * @var mixed
     */
    private $value;

    /**
     * Kreira novi atribut prema zadanom imenu i vrijednosti(ma)
     * HTMLAttribute constructor.
     * @param {string} $name naziv atributa
     * @param {mixed} $value vrijednosti atributa
     */
    public function __construct(string $name, $value){
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Atributu dodaje novu vrijednost. Nije dopusteno dupliciranje
     * @param $value
     */
    public function add_value($value){
        if ($value !== null){
            if (!is_array($this->value)){  //if the value is not an array, turn in into an array containing the former $value and new $value
                if ($this->value !== $value){ //if not a duplicate
                    $arr = array();
                    array_push($arr, $value, $this->value);

                    $this->value = $arr; //set $this->value to the newly formed array
                }

            } else {
                array_push($this->value, $value);
            }
        }
    }

    /**
     * Atributu dodaje vise novih vrijednosti. Potrebno je paziti na dupliciranje.
     * @param $values
     */
    public function add_values($values){
        foreach($values as $value){
            $this->add_value($value);
        }
    }

    /**
     * Uklanja postojecu vrijednost atributa
     * @param {string} $value vrijednost koju je potrebno ukloniti
     */
    public function remove_value(string $value){
        if (is_array($value)) {
            if (($key = array_search($value, $this->value)) !== false) {
                unset($this->value[$key]);
            }
        } else if ($value === $this->value){
            $this->value = null;
        }

    }

    /**
     * Vraca naziv atributa
     * @return {string} ime atributa
     */
    public function get_name() : string{
        return $this->name;
    }

    /**
     * Vraca vrijednosti atributa u formatu pogodnom za zapis u tagu.
     * Ex: key="value"
     * Vraca se "value"
     */
    public function get_values(){
        $value_str = '';
        if (is_array($this->value)){
            foreach ($this->value as $val){
                $value_str .= $val.' ';
            }
        } else {
            $value_str .= $this->value;
        }

        return $value_str;
    }

    /**
     * Zapis atributa i vrijednosti u obliku znakovnog niza.
     */
    public function __toString(){
        return  $this->get_name() . "=" . "'". $this->get_values() . "'";
    }


}