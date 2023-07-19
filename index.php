<?php

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

require 'app/includes/config.php';
require 'app/includes/chargementClasses.inc.php';

session_start();

$this->debug_to_console("je suis avant router");

(new Routeur)->router();

$this->debug_to_console("je suis apres router");
