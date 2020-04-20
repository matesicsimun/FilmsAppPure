<?php
require_once "HTMLElement.php";

class HTMLBodyElement extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("body", true);
    }
}