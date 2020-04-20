<?php

/**
 * Implementacija cvora koji predstavlja čvor u stablu
 * Class HTMLTextNode
 */
class HTMLTextNode extends HTMLNode
{
    /**
     * Sadrzaj tekstualnog cvora
     */
    private $text;

    /**
     * Stvara novi tekstualni cvor zadanog sadrzaja
     * @param {string} $text tekst cvora
     */
    public function __construct(string $text){
        $this->text = $text;
    }

    /**
     * Alias metode __toString u situacijama kada bi ova
     * metoda bila semanticki prikladnija
     */
    public function get_text() : string{
        return $this->text;
    }

    /**
     * Vraca sadrzaj cvora.
     */
    public function __toString() : string{
        return $this->text;
    }

    /**
     * @inheritDoc
     */
    public function get_html() : string
    {
        return $this->get_text();
    }
}
