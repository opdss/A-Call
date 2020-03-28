<?php

namespace App\Game;

use App\Game\AcallPoker;

class TakePoker
{
  //手牌长度
  private $length;

  //牌型: 1 单， 2 对， 3 三炸， 4四炸， 5 单顺， 6，双顺
  private $type;

  //牌类型级别 1 最小，按值比大小； 2.大于level:1(type:5) 3 直接大于level:1,2(type:6)
  private $level;

  //比较值 同类型级别比谁大就大, 从大到小第一张牌值
  private $first;

  //按牌面=>牌值从大到小排序 array('1a'=>10, '2a' => 10)
  private $cards;

  private $inputCard;

  private $isOk = false;

  private $prop = ['length', 'type', 'level', 'first', 'cards', 'isOk', 'inputCard'];

  public function __construct(array $cards)
  {
      $this->inputCard = $cards;
      $this->checkCards($cards);
  }

  public static function factory(array $cards):TakePoker
  {
      return new self($cards);
  }

  public function __get($name)
  {
      if (in_array($name, $this->prop)) {
          return $this->{$name};
      }
      return null;
  }

  public function toArray()
  {
      $data = [];
      foreach ($this->prop as $prop) {
          $data[$prop] = $this->{$prop};
      }
      return $data;
  }

  public function compare(TakePoker $tpoker) {
      return self::toCompare($this, $tpoker);
  }

    /**
     *
     * @param TakePoker $tpoker1
     * @param TakePoker $tpoker2
     * @return int  -1：牌型有问题，没法比较， 1：$tpoker1>$tpoker2,  0:$tpoker1<$tpoker2
     */
    public static function toCompare(TakePoker $tpoker1, TakePoker $tpoker2)
    {
        if (!$tpoker1->isOk || !$tpoker2->isOk) return 0;
        if ($tpoker1->level == $tpoker2->level) {
            if ($tpoker1->type == $tpoker2->type) {
                if ($tpoker1->length == $tpoker2->length) {
                    return $tpoker1->first > $tpoker2->first ? 1 : ($tpoker1->first == $tpoker2->first ? 0 : -1);
                }
            }
            return 0;
        } else {
            return $tpoker1->level > $tpoker2->level ? 1 : -1;
        }
    }

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
  private function checkCards(array $cards)
  {
    $this->length = count($cards);
    if (count(array_unique($cards)) !== $this->length) {
      return $this->isOk;
    }

    $this->cards = array();
    foreach ($cards as $card) {
      $this->cards[$card] = AcallPoker::getCardVal($card);
    }

    //按牌值从大到小
    arsort($this->cards);
    $card_vals = array_values($this->cards); //十进制后的牌值

    switch ($this->length) {
      case 1:
        $this->isOk = true;
        $this->type = AcallPoker::CARD_TYPE_DAN;
        $this->level = AcallPoker::CARD_LEVEL_1;
        $this->first = $card_vals[0];
        break;
      case 2:
        list($a, $b) = $card_vals;
        $this->isOk = $a == $b ? true : false;
        $this->type = AcallPoker::CARD_TYPE_DUI;
        $this->level = AcallPoker::CARD_LEVEL_1;
        $this->first = $a;
        break;
      case 3:
        list($a, $b, $c) = $card_vals;
        if ($a == $b && $b == $c) {
          $this->isOk = true;
          $this->type = AcallPoker::CARD_TYPE_ZHA_SAN;
          $this->level = AcallPoker::CARD_LEVEL_2;
          $this->first = $a;
        }
        //三个的顺子判断
        elseif (in_array($a, array(AcallPoker::CARD_6, AcallPoker::CARD_7, AcallPoker::CARD_J,AcallPoker::CARD_Q,AcallPoker::CARD_K))) {
          if ($a == ($b + 1) && $b == ($c + 1)) {
            $this->isOk = true;
            $this->type = AcallPoker::CARD_TYPE_DANSHUN;
            $this->level = AcallPoker::CARD_LEVEL_1;
            $this->first = $a;
          }
        }
        break;
      case 4:
        list($a, $b, $c, $d) = $card_vals;
        if ($a == $b && $b == $c && $c == $d) {
          $this->isOk = true;
          $this->type = AcallPoker::CARD_TYPE_ZHA_SI;
          $this->level = AcallPoker::CARD_LEVEL_3;
          $this->first = $a;
        }
        //双顺子的判断
        elseif ($a == $b && in_array($a, array(AcallPoker::CARD_5, AcallPoker::CARD_6, AcallPoker::CARD_7, AcallPoker::CARD_X, AcallPoker::CARD_J,AcallPoker::CARD_Q,AcallPoker::CARD_K))) {
          if ($b == ($c + 1) && $c == $d) {
            $this->isOk = true;
            $this->type = AcallPoker::CARD_TYPE_SHUANGSHUN;
            $this->level = AcallPoker::CARD_LEVEL_1;
            $this->first = $a;
          }
        }
        //四张单顺子的判断
        elseif (in_array($a, array(AcallPoker::CARD_7, AcallPoker::CARD_Q, AcallPoker::CARD_K))) {
          if ($a == ($b + 1) && $b == ($c + 1) && $c == ($d + 1)) {
            $this->isOk = true;
            $this->type = AcallPoker::CARD_TYPE_DANSHUN;
            $this->level = AcallPoker::CARD_LEVEL_1;
            $this->first = $a;
          }
        }
        break;
      case 5:
        if (implode('', $card_vals) == implode('', array(AcallPoker::CARD_K, AcallPoker::CARD_Q,AcallPoker::CARD_J,AcallPoker::CARD_X, AcallPoker::CARD_9))) {
          $this->isOk = true;
          $this->type = AcallPoker::CARD_TYPE_DANSHUN;
          $this->level = AcallPoker::CARD_LEVEL_1;
          $this->first = $card_vals[0];
        }
        break;
      case 6:
        list($a, $b, $c, $d, $e, $f) = $card_vals;
        if (in_array($a, array(AcallPoker::CARD_6, AcallPoker::CARD_7, AcallPoker::CARD_J,AcallPoker::CARD_Q, AcallPoker::CARD_K))) {
          if ($a == $b && $c == $d && $e == $f && $b == ($c + 1) && $d == ($e + 1)) {
            $this->isOk = true;
            $this->type = AcallPoker::CARD_TYPE_SHUANGSHUN;
            $this->level = AcallPoker::CARD_LEVEL_1;
            $this->first = $a;
          }
        }
        break;
      case 8:
        list($a, $b, $c, $d, $e, $f, $g, $h) = $card_vals;
        if (in_array($a, array(AcallPoker::CARD_7, AcallPoker::CARD_Q, AcallPoker::CARD_K))) {
          if ($a == $b && $c == $d && $e == $f && $g == $h && $b == ($c + 1) && $d == ($e + 1) && $f == ($g + 1)) {
            $this->isOk = true;
            $this->type = AcallPoker::CARD_TYPE_SHUANGSHUN;
            $this->level = AcallPoker::CARD_LEVEL_1;
            $this->first = $a;
          }
        }
        break;
      default:
        if ($this->length == 10 && implode('', $card_vals) == implode('', array(AcallPoker::CARD_K, AcallPoker::CARD_K, AcallPoker::CARD_Q,AcallPoker::CARD_Q,AcallPoker::CARD_J,AcallPoker::CARD_J,AcallPoker::CARD_X,AcallPoker::CARD_X, AcallPoker::CARD_9,AcallPoker::CARD_9))) {
          $this->isOk = true;
          $this->type = AcallPoker::CARD_TYPE_SHUANGSHUN;
          $this->level = AcallPoker::CARD_LEVEL_1;
          $this->first = $card_vals[0];
        }
        break;
    }
    return $this->isOk;
  }
}