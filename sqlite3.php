<?php

class DB extends SQLite3
{
    function __construct()
    {
        $this->open('av.db');
    }
}

$db = new DB();
//sukuriam lentele ir idedam testinius duomenis
$db->exec('CREATE TABLE IF NOT EXISTS products (
   SKU INTEGER PRIMARY KEY,
   title VARCHAR(50),
   img VARCHAR(255),
   price INTEGER(10),
   enabled BIT DEFAULT 0,
   created DATETIME DEFAULT CURRENT_TIMESTAMP,
   modified DATETIME  NULL,
   expires DATETIME  NULL
 )');

$result = $db->query('SELECT * FROM products');

if (count($result->fetchArray()) > 1) {

} else {
    $db->exec("INSERT INTO products (title, img, price, created, enabled)
     VALUES ('Mašina', '001.png', 20000,  datetime(), 1 )");
     $db->exec("INSERT INTO products (title, img, price, created)
     VALUES ('PC', '002.png', 1000, datetime())");
     $db->exec("INSERT INTO products (title, img, price, created)
     VALUES ('3D printeris', '003.png', 2000, datetime())");
     $db->exec("INSERT INTO products (title, img, price, created)
     VALUES ('Selfie-stick', '004.png', 50, datetime())");
};

?>

