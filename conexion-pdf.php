<?php
// simple conexion a la base de datos
/*   $conn = mysqli_connect("89.46.111.52", "Sql1142897", "o20556190q");
    mysqli_select_db($conn, "Sql1142897_5");
    mysqli_set_charset($conn, 'utf8');*/

/*    $conn = mysqli_connect("localhost", "root", "root");
    mysqli_select_db($conn, "web");
    mysqli_set_charset($conn, 'utf8');
    return $conn;*/
function connect(){
    $conn = mysqli_connect("localhost", "root", "root");
    mysqli_select_db($conn, "web");
    mysqli_set_charset($conn, 'utf8');
    return $conn;
}

?>