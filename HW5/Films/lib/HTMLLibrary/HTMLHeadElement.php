<?php

class HTMLHeadElement extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("head", true);
    }
}