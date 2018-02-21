<?php

    error_reporting( ~E_DEPRECATED & ~E_NOTICE );
 
    define('HOST', 'localhost');
    define('USERNAME', 'scfhc');
    define('PASSWORD', 'Aksach2378');
    define('DATABASE', 'scfhc');
 
$mysqli = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
if ( !$mysqli ) {
    die("Connection failed : " . mysqli_error());
}


?>