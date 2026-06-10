<?php
$host = 'localhost';
$port = '5433';
$db   = 'helpdesk';
$user = 'postgres';
$pass = 'olcoln';

$conn_string = "host=$host port=$port dbname=$db user=$user password=$pass";
$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    echo "Failed to connect to PostgreSQL.\n";
    exit;
}

$query = "SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema'";
$result = pg_query($dbconn, $query);

if (!$result) {
    echo "An error occurred.\n";
    exit;
}

while ($row = pg_fetch_assoc($result)) {
    $table = $row['tablename'];
    $count_query = "SELECT COUNT(*) FROM \"$table\"";
    $count_result = pg_query($dbconn, $count_query);
    $count_row = pg_fetch_assoc($count_result);
    $count = $count_row['count'];
    echo "Table: $table - Count: $count\n";
}

pg_close($dbconn);
