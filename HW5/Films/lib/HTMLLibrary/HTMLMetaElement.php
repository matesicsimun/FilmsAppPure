<?php


class HTMLMetaElement extends HTMLElement
{
    /*
     * TODO - make it work so that the user can send an array of attributes
     */
    public function __construct(array $attributes)
    {
        parent::__construct("meta", false);

        foreach ($attributes as $attribute_key => $attr_value){
            $html_attribute = new HTMLAttribute($attribute_key, $attr_value);
            $this->add_attribute($html_attribute);
        }
    }
}