<?php

namespace App\Game;

use AcallPoker;

class TakePoker
{

  //出手数量
  static $count = 0;

  //手牌长度
  private $length;

  //牌型: 1 单， 2 对， 3 三炸， 4四炸， 5 单顺， 6，双顺
  private $type;

  //牌类型级别 1 最小，按值比大小； 2.大于class:1(type:5) 3 直接大于class:1,2(type:6)
  private $class;

  //比较值 同类型级别比谁大就大, 从小到大第一张牌值
  private $start;

  //按牌值从小到大排序 array('1a'=>10, '2a' => 10)
  private $cards;

  //去除花色牌值
  private $catd_vals;

  private $isOk = false;

  /**
   * 检查出牌是否合法
   * 1张，都合法
   * 2张：一对
   * 3张：1.三个的炸，2.三个的顺子
   * 4张：1.四个的炸，2.四个的顺子，3.双顺子，如6677
   * 5张：必须是 9,10,J,Q,K 顺子
   * 6张：三连顺 如556677
   * 8张：四连顺
   * 10张：五连顺，只有991010JJQQKK
   * @param array $cards
   */
  public function checkCards(array $cards)
  {
    $this->length = count($cards);
    if (count(array_unique($cards)) !== $this->length) {
      return $this->isOk;
    }

    $this->cards = array();
    foreach ($cards as $card) {
      $this->cards[$card] = AcallPoker::card2dec($card);
    }

    arsort($this->cards);
    $card_vals = array_values($this->cards); //十进制后的牌值

    switch ($this->length) {
      case 1:
        $this->isOk = true;
        $this->type = 1;
        $this->class = 1;
        $this->start = $card_vals[0];
        break;
      case 2:
        list($a, $b) = $card_vals;
        $this->isOk = $a == $b ? true : false;
        $this->type = 2;
        $this->class = 1;
        $this->start = $a;
        break;
      case 3:
        list($a, $b, $c) = $card_vals;
        if ($a == $b && $b == $c) {
          $this->isOk = true;
          $this->type = 5;
          $this->class = 2;
          $this->start = $a;
        } elseif (in_array($a, array(3, 4, 7, 8, 9))) { //三个的顺子判断
          if ($a == ($b + 1) && $b == ($c + 1)) {
            $this->isOk = true;
            $this->type = 3;
            $this->class = 1;
            $this->start = $a;
          }
        }
        break;
      case 4:
        list($a, $b, $c, $d) = $card_vals;
        if ($a == $b && $b == $c && $c == $d) {
          $this->isOk = true;
          $this->type = 6;
          $this->class = 3;
          $this->start = $a;
        } elseif ($a == $b && in_array($a, array(2, 3, 4, 6, 7, 8, 9))) { //双顺子的判断
          if ($b == ($c + 1) && $c == $d) {
            $this->isOk = true;
            $this->type = 4;
            $this->class = 1;
            $this->start = $a;
          }
        } elseif (in_array($a, array(4, 8, 9))) { //四个顺子的判断
          if ($a == ($b + 1) && $b == ($c + 1) && $c == ($d + 1)) {
            $this->isOk = true;
            $this->type = 3;
            $this->class = 1;
            $this->start = $a;
          }
        }
        break;
      case 5:
        if (implode('', $card_vals) == '98765') {
          $this->isOk = true;
          $this->type = 3;
          $this->class = 1;
          $this->start = $card_vals[0];
        }
        break;
      case 6:
        list($a, $b, $c, $d, $e, $f) = $card_vals;
        if (in_array($a, array(3, 4, 7, 8, 9))) {
          if ($a == $b && $c == $d && $e == $f && $b == ($c + 1) && $d == ($e + 1)) {
            $this->isOk = true;
            $this->type = 4;
            $this->class = 1;
            $this->start = $a;
          }
        }
        break;
      case 8:
        list($a, $b, $c, $d, $e, $f, $g, $h) = $card_vals;
        if (in_array($a, array(4, 8, 9))) {
          if ($a == $b && $c == $d && $e == $f && $g == $h && $b == ($c + 1) && $d == ($e + 1) && $f == ($g + 1)) {
            $this->isOk = true;
            $this->type = 4;
            $this->class = 1;
            $this->start = $a;
          }
        }
        break;
      default:
        if ($this->length == 10 && implode('', $card_vals) == '9988776655') {
          $this->isOk = true;
          $this->type = 4;
          $this->class = 1;
          $this->start = $card_vals[0];
        }
        break;
    }
    return $this->isOk;
  }
}