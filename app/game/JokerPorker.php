<?php

namespace App\Game;

class JokerPorker
{
  //玩家数量
  const PLAYER = 4;

  //黑桃 4:spade； 红桃 3:heart； 草花 2:club； 方片 1:diamond
  const SUITS = array(
    1 => '黑桃',
    2 => '红桃',
    3 => '梅花',
    4 => '方块'
  );

  //4, 5, 6, 7, 9, 10, J, Q, K, A, 2, 8, 3
  const CARDS = array(
    '1' => '4',
    '2' => '5',
    '3' => '6',
    '4' => '7',
    '5' => '9',
    '6' => '10',
    '7' => 'J',
    '8' => 'Q',
    '9' => 'K',
    'a' => 'A',
    'b' => '2',
    'c' => '8',
    'd' => '3'
  );

  /**
   * 牌型
   * @var array
   */
  public static $card_type = array(
    0 => '非赢牌',
    1 => '对K或者以上',
    2 => '对子',
    3 => '三条',
    4 => '顺子',
    5 => '同花',
    6 => '葫芦',
    7 => '四条',
    8 => '同花顺',
    9 => '五条',
    10 => '带赖子皇家同花顺',
    11 => '皇家同花顺'
  );

  public static $card_list;

  public function __construct()
  {
  }

  public function genCardList()
  {
    self::$card_list  = array();
    foreach (self::SUITS as $suit) {
      foreach (self::CARDS as $card) {
        self::$card_list[] = $suit . $card;
      }
    }
    shuffle(self::$card_list);
    return self::$card_list;
  }
}
