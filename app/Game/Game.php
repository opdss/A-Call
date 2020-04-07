<?php
/**
 * Created by PhpStorm.
 * User: wuxin
 * Date: 2020/4/7
 * Time: 12:02
 * Desc:
 */
namespace App\Game;

class Game{

    /**
     * 叫牌间隔时间
     * @var int
     */
    private $callCardLimitTime = 30;

    /**
     * 出牌间隔时间
     * @var int
     */
    private $takeCardLimitTime = 30;

    /**
     * 房间
     * @var null | \Room
     */
    private $room = null;

    /**
     * 玩家
     * @var null | array
     */
    private $players = null;

    private $status;

    public function __construct()
    {
    }

    public function startCallCard()
    {
        $arr = [0 => [], 1=> []];
        foreach ($this->players as $player) {
            if ($player->isFirstCall) {
                $arr[0][] = $player;
            } else {
                $arr[1][] = $player;
            }
        }
        $arr = $arr[0]+$arr[1];
        foreach ($arr as $player) {
            $this->sendMsg($player, Cmd::GAME_CALL_CARD);

        }
    }

    public function sendMsg($player, $cmd)
    {

    }
}