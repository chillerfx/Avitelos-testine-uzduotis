<?php
error_reporting(E_ALL ^ E_NOTICE);
include 'sqlite3.php';


$p = '';
$p = $_GET['p'];

function add($x, $y) { return $x + $y; }
function subtract($x, $y) { return $x - $y; }
function multiply($x, $y) { return $x * $y; }
function divide($x, $y) { return $x / $y; }
function getJSON($db) {
    $res = $db->query('SELECT * FROM products ORDER BY enabled desc');

    $data = array();
    while ($rec = $res->fetchArray(SQLITE3_ASSOC)) {
        $rec["data"] = $rec["SKU"]; //putting chilrden into item "data" property
        $data[] = $rec;
    }
    //output json
    echo json_encode($data);
}
$operators = array('add', 'subtract', 'multiply', 'divide');

if ($p == '2') {
     //Nuskaityti dabar enablinta produkta, pakeisti jo kaina, enablinti sekanti, grazinti nauja json su enabinto pabaigos data
    $res = $db->query('SELECT * FROM products WHERE enabled = 1');

    $rec = $res->fetchArray(SQLITE3_ASSOC);
    $SKU = $rec['SKU'];
    $op = $rec['price'];
    $ep = $rec['expires'];
    //check date
    $date = date_create();
    $d = date_format($date, 'U');
    $dd = $ep - $d;
    if ($dd <= 1) {
    $np = call_user_func_array($operators[array_rand($operators)], array($op, rand(1,10)));

    //pakeicia kaina
    $s = $db->prepare('UPDATE products SET price = :np, enabled = 0, modified = datetime() WHERE SKU = :SKU');
    $s->bindValue(':SKU', $SKU);
    $s->bindValue(':np', $np);
    $res = $s->execute();
    $s->close();
    //iselektina nauja ir prideda pabaigos data
    $res = $db->query('SELECT * FROM products ORDER BY datetime(modified) ASC Limit 1');
    $rec = $res->fetchArray(SQLITE3_ASSOC);
    $SKU = $rec['SKU'];

    $ed = new DateTime();
    $ed->modify("+30 minutes");
    $exd = $ed->getTimestamp();
    $s = $db->prepare('UPDATE products SET enabled = 1, expires =:ed WHERE SKU = :SKU');
    $s->bindValue(':SKU', $SKU);
    $s->bindValue(':ed', $exd);
    $res = $s->execute();
    $s->close();
    getJSON($db);
    return;
    } else{
        getJSON($db);
        return;
    }
}
if (isset($_GET['i'])) {
    $i = $_GET['i'];

    $s = $db->prepare('SELECT * FROM products WHERE SKU = :SKU');
    $s->bindValue(':SKU', $i);
    $res = $s->execute();

    $data = array();
    while ($rec = $res->fetchArray(SQLITE3_ASSOC)) {
        $rec["data"] = $rec["SKU"]; //putting chilrden into item "data" property
        $data[] = $rec;
    }
    $s->close();
    //output json
    echo json_encode($data);
    return;
}
?>
<!DOCTYPE html>
<html class="no-js" ng-app="avStore">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <script type="text/javascript" src="js/angular.min.js"></script>
        <script type="text/javascript" src="js/angular-route.min.js"></script>
        <script type="text/javascript" src="js/timer.js"></script>
        <script type="text/javascript" src="js/app.js"></script>
    </head>
    <body  >
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#/">Avitelos Testine užduotis</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        </div><!--/.navbar-collapse -->
      </div>
    </nav>


    <div class="jumbotron">

    <div ng-view></div>

        <div class="container">
          <footer>
            <p> Artūras Sotničenko &copy;  2015</p>
          </footer>
        </div>
    </div>
  <!-- /container -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>
    </body>
</html>
