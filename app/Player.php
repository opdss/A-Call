<?php
/**
 * Created by PhpStorm.
 * User: wuxin
 * Date: 2020/3/28
 * Time: 12:22
 * Desc:
 */

class Player
{
    /**
     * 第一个庄
     * @var bool
     */
    private $firstCall = false;

    /**
     * 叫过牌 ['13', '24']
     * @var null|array
     */
    private $callCard = null;

    /**
     * 是否庄家
     * @var bool
     */
    private $currentCall = false;

    public function isFirstCall()
    {
        return $this->firstCall;
    }
}