<?php

/**
 * Razred sluzi oznacavanju razreda koji predstavljaju cvorove stabla
 * Class HTMLNode
 */
abstract class HTMLNode
{
    /**
     * Vraca html cvor kao string.
     * @return mixed
     */
    public abstract function get_html();
}