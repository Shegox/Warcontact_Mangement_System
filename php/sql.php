<?php
include_once  __DIR__ ."/constants.php";

function sql_read($sql)
{
    $conn = connect();
    $result = $conn->query($sql);
    // $result = $result->fetch_all(MYSQLI_BOTH);
    // Workaround due to not working fetch_all with some versions of mysql on windows
    $rows = [];
    while ($row = $result->fetch_array()) {
        $rows[] = $row;
    }
    $result = $rows;
    $conn->close();
    return $result;
}

function sql_write($sql)
{
    $conn = connect();
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

function connect()
{
    $conn = new mysqli ('localhost', $GLOBALS["USER"], $GLOBALS["PASSWORD"], $GLOBALS["DATABASE"]);
    return $conn;
}