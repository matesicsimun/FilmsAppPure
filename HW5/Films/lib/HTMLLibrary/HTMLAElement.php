<?php


class HTMLAElement extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("a", true);
    }
}