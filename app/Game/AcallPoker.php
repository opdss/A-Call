<?php

namespace App\Game;


class AcallPoker
{
    const PLAYER = 4;
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
    CARD_A = 10,
    CARD_2 = 11,
    CARD_8 = 12,
    CARD_3 = 13;


    //黑桃 4:spade； 红桃 3:heart； 草花 2:club； 方片 1:diamond
    const SUITS = array(
        self::SUITS_SPADE => '黑桃',
        self::SUITS_HEART => '红桃',
        self::SUITS_DIAMOND => '梅花',
        self::SUITS_CLUB => '方块'
    );

    //4, 5, 6, 7, 9, 10, J, Q, K, A, 2, 8, 3
    const CARDS = array(
        self::CARD_4 => '4',
        self::CARD_5 => '5',
        self::CARD_6 => '6',
        self::CARD_7 => '7',
        self::CARD_9 => '9',
        self::CARD_A => '10',
        self::CARD_J => 'J',
        self::CARD_Q => 'Q',
        self::CARD_K => 'K',
        self::CARD_A => 'A',
        self::CARD_2 => '2',
        self::CARD_8 => '8',
        self::CARD_3 => '3'
    );

  public static $allow_call = [
    self::CARD_4, self::CARD_5, self::CARD_6, self::CARD_7, self::CARD_9, self::CARD_X, self::CARD_J, self::CARD_Q, self::CARD_K
  ];


    public static function genCardList($shuffle = true) : array
    {
        $card_list  = array();
        foreach (array_keys(self::SUITS) as $suit) {
            foreach (array_keys(self::CARDS) as $card) {
                $card_list[] = $suit . dechex($card);
            }
        }
        $shuffle && shuffle($card_list);
        return $card_list;
    }

    public static function dealCards()
    {
        $cardList = self::genCardList();
        return array_chunk($cardList, count($cardList)/self::PLAYER);
    }


  /**
   * 获取去除花色后的牌面值
   * @param $poker
   */
  public static function getCardVal($card): int
  {
    return hexdec($card) % 16;
  }

  public static function getCardSuit($card): int
  {
      return round(hexdec($card) / 16);
  }

  public static function getCardInfo($card): array
  {
      $v = hexdec($card);
      return array(
          'card' => $card,
          'suit' => round($v/16),
          'val' => $v%16
      );
  }

  /*
   * 对一组牌排序
   */
  public static function sort(array $cards, $sort = 1): array
  {
    $res = array();
    foreach ($cards as $card) {
      $res[$card] = self::getCardVal($card);
    }
    $sort == 1 ? arsort($res) : asort($res);
    return array_keys($res);
  }

  public static function allowCall($card)
  {
    if (in_array(self::getCardVal($card), self::$allow_call)) {
      return true;
    }
    return false;
  }

}