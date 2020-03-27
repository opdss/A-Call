<?php

namespace App\Game;

class AcallPoker
{
  const
    SUITS_SPADE = 1, //黑桃
    SUITS_HEART = 2, //红桃
    SUITS_CLUB = 3, //方块 
    SUITS_DIAMOND = 4; //梅花

  const
    CARD_TYPE_DAN = 1, //单牌
    CARD_TYPE_DUI = 2, //对子
    CARD_TYPE_ZHA_SAN = 3, //三张炸
    CARD_TYPE_ZHA_SI = 4, //四张炸
    CARD_TYPE_DANSHUN = 5, //单顺子
    CARD_TYPE_SHUANGSHUN = 6; //双顺子

  //牌类型级别 1 最小，按值比大小； 2.大于class:1(type:5) 3 直接大于class:1,2(type:6)
  const
    CARD_LEVEL_1 = 1, //普通的 单，对，顺，
    CARD_LEVEL_2 = 2, //三张炸
    CARD_LEVEL_3 = 3; //四张炸

  const
    CARD_4 = 1,
    CARD_5 = 2,
    CARD_6 = 3,
    CARD_7 = 4,
    CARD_9 = 5,
    CARD_X = 6,
    CARD_J = 7,
    CARD_Q = 8,
    CARD_K = 9,
    CARD_A = 'a',
    CARD_2 = 'b',
    CARD_8 = 'c',
    CARD_3 = 'd';

  public static $allow_call = [
    self::CARD_4, self::CARD_5, self::CARD_6, self::CARD_7, self::CARD_9, self::CARD_X, self::CARD_J, self::CARD_Q, self::CARD_K
  ];



  /**
   * 获取去除花色后的牌面值
   * @param $poker
   */
  public static function card2dec($card): int
  {
    $res = hexdec($card) % 16;
    return $res;
  }

  /*
   * 对一组牌排序
   */
  public static function sort(array $cards, $sort = 1): array
  {
    $res = array();
    foreach ($cards as $card) {
      $res[$card] = self::card2dec($card);
    }
    $sort == 1 ? arsort($res) : asort($res);
    return array_keys($res);
  }

  public static function allowCall($card)
  {
    $res = dechex(hexdec($card) % 16);
    if (in_array($res, self::$allow_call)) {
      return true;
    }
    return false;
  }

  /**
   * 判断是否对子
   *
   * @param array $cards
   * @return void
   */
  public static function typeIsDui(array $cards)
  {
    if (count($cards) === 2) {
      list($a, $b) = $cards;
      return $a == $b;
    }
    return false;
  }

  public static function typeIsZha(array $cards)
  {
    $len = count($cards);
    if ($len === 3) {
      list($a, $b, $c) = $cards;
      return $a == $b && $b == $c;
    } elseif ($len === 4) {
      list($a, $b, $c, $d) = $cards;
      return $a == $b && $b == $c && $c == $d;
    }
    return false;
  }

  /**
   * @param Tpoker $tpoker1
   * @param Tpoker $tpoker2
   * @return int
   */
  static public function compare(Tpoker $tpoker1, Tpoker $tpoker2)
  {
    if (!$tpoker1->isOk || !$tpoker2->isOk) return 0;
    if ($tpoker1->class == $tpoker2->class) {
      if ($tpoker1->type == $tpoker2->type) {
        if ($tpoker1->length == $tpoker2->length) {
          return $tpoker1->start > $tpoker2->start ? 1 : ($tpoker1->start == $tpoker2->start ? 0 : -1);
        } else {
          return 0;
        }
      } else {
        return 0;
      }
    } else {
      return $tpoker1->class > $tpoker2->class ? 1 : -1;
    }
  }

  public static function typeIsShun(array $cards)
  {
    $len = count($cards);
  }
}