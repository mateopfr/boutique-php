<?php
require_once 'Table.php';

class Categories extends Table
{
    public function __construct()
    {
        parent::__construct('category');
    }
}