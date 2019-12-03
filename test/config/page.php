<?php
/**
 * Configuration file for page which can create and put together web pages
 * from a collection of views. Through configuration you can add the
 * standard parts of the page, such as header, navbar, footer, stylesheets,
 * javascripts and more.
 */

return [
    "layout" => [
        "region" => "layout",
        // Change here to use your own templatefile as layout
        "template" => "test/layout",
        "data" => [
            "lang" => "sv",
        ],
    ],

    // These views are always loaded into the collection of views.
    "views" => [],
];
