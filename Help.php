<?php namespace App\Lib\Game;

class Help {

    /**
     * 获取 全部 / 单个 玩法的配置
     * @param $seriesId
     * @param string $methodId
     * @return array
     */
    static function getMethodConfig($seriesId) {

        $config = require __DIR__ . "/config/method_{$seriesId}.php";
        return $config;
    }
}

