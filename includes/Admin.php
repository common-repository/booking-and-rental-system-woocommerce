<?php

namespace RnbLite;

/**
 * Admin Class handler
 */
class Admin
{
    /**
     * Class initialize
     */
    function __construct()
    {
        new Admin\Product_Meta_Boxes();
        new Admin\Menu();
    }
}