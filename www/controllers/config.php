<?php

return [
    [
        "method" => "GET",
        "uri" => "/",
        "controller" => "redirect"
    ],
    [
        "method" => "GET",
        "uri" => "/city",
        "controller" => "listCity"
    ],
    [
        "method" => "GET",
        "uri" => "/city/add",
        "controller" => "addCity"
    ],
    [
        "method" => "POST",
        "uri" => "/city",
        "controller" => "submitCity"
    ],
    [
        "method" => "GET",
        "uri" => "/city/{id}",
        "controller" => "getCity"
    ],
    [
        "method" => "POST",
        "uri" => "/city/{id}/remove",
        "controller" => "removeCity"
    ]
];
