<?php
/**
 * Created by PhpStorm.
 * User: wuxin
 * Date: 2020/4/7
 * Time: 12:11
 * Desc:
 */

namespace App\Game;

class Cmd
{
    const GAME_CALL_CARD = 10;

    const
        GAME_STATUS_WAITING = 0, //等人
        GAME_STATUS_CALL_CARD = 1, // 叫牌
        GAME_STATUS_PLAY = 2, //玩牌
        GAME_STATUS_OVER = 3; //结束
}