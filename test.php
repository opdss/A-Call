<?php

include  'vendor/autoload.php';

$s = hexdec('4d');
echo $s;
echo PHP_EOL;

echo $s%16;
echo PHP_EOL;

echo $s/16;
echo PHP_EOL;

$s = \App\Game\AcallPoker::dealCards();

var_dump($s);
/*$cards = \App\Game\AcallPoker::genCardList();

$arr = array_slice($cards, 5, 10);


$cards = array();
foreach ($arr as $card) {
    $cards[$card] = \App\Game\AcallPoker::getCardVal($card);
}

var_dump($arr);
var_dump($cards);
arsort($cards);
var_dump($cards);

$a = \App\Game\TakePoker::factory(['11', '22','21', '12']);
$b = \App\Game\TakePoker::factory(['13', '24', '23', '34']);
var_dump($a->toArray());
var_dump($b->toArray());
var_dump($a->compare($b));*/

