<?php

namespace App\Lib\Game;

use App\Lib\BaseCache;
use App\Models\LotteryList;

/**
 * 游戏相关逻辑
 * Class Lottery
 * @package App\Lib\Game
 */
class Lottery
{
    use BaseCache;

    public static $_lotteries = [];
    public static $_methods = [];
    public static $_config = [];

    /**
     * 获取彩种详情 - 包含玩法数据
     * @param $lotterySign
     * @return mixed
     * @throws \Exception
     */
    public static function getLottery($lotterySign)
    {
        $id = strtolower($lotterySign);

        if (isset(self::$_lotteries[$id])) {
            return self::$_lotteries[$id];
        }

        // 获取彩种
        $lottery = LotteryList::where('en_name', $id)->where('status', 1)->first();
        if (!$lottery) {
            throw new \RuntimeException("彩种:{$lottery->cn_name}, 数据库配置 - 不存在!");
        }
        // 检测游戏状态
        if ($lottery->status !== 1) {
            throw new \RuntimeException("彩种:{$lottery->cn_name}, 游戏未开启!");
        }
        // 获取玩法
        $series = $lottery->series;
        $methods = @require __DIR__ . "/Config/method_{$series}.php";
        if (!$methods) {
            throw new \RuntimeException("彩种:{$lottery->cn_name}, 玩法 - 不存在!");
        }
        $lottery->methods = $methods;
        self::$_lotteries[$id] = $lottery;
        return $lottery;
    }

    // 获取所有彩种
    public static function getAllLottery()
    {
        $lotteries = LotteryList::where('status', 1)->get();
        foreach ($lotteries as $lottery) {
            // 检测游戏状态
            if ($lottery->status !== 1) {
                throw new \RuntimeException("彩种:{$lottery->cn_name}, 游戏未开启!");
            }
            $series = $lottery->series_id;
            // 获取玩法
            if (empty(self::$_methods[$series])) {
                $methods = include __DIR__ . "/config/method_{$series}.php";
                if (!$methods) {
                    throw new \RuntimeException("彩种:{$lottery->cn_name}, 玩法 - 不存在(get all lotteries)!");
                }
                self::$_methods[$series] = $methods;
            }
            $lottery->methods = self::$_methods[$series];
            self::$_lotteries[$lottery->en_name] = $lottery;
        }
        return self::$_lotteries;
    }

    /**
     * 获取 [系列] 玩法 对象
     * @param $series
     * @param $group
     * @param $methodId
     * @return mixed
     * @throws \Exception
     */
    public static function getMethodObject($series, $group, $methodId)
    {
        // 获取配置
        $config = self::getMethodConfig($series, $methodId);
        if (!$config) {
            return "玩法{$methodId}配置:不存在!";
        }
        // 玩法
        $class = "\\App\\Lib\\Game\\Method\\" . ucfirst($series) . "\\" . $group . "\\" . $methodId;
        if (!class_exists($class)) {
            return "玩法:{$methodId} 不存在!";
        }
        // 获取玩法对象
        $method = new $class($methodId, $series, $config);
        return $method;
    }

    /**
     * 获取 [系列] 玩法 的配置
     * @param $seriesSign
     * @return mixed
     * @throws
     */
    public static function getAllMethodConfig($seriesSign)
    {

        $data = [];
        if (self::_hasCache('method_config')) {
            $data = self::_getCacheData('method_config');
            if (isset($data[$seriesSign])) {
                return $data[$seriesSign];
            }
        }
        $config = include __DIR__ . "/config/method_{$seriesSign}.php";
        if ($config) {
            $data[$seriesSign] = $config;
            self::_saveCacheData('method_config', $data);
            return $config;
        }
        return [];
    }
    /**
     * 获取 [指定] 玩法 的配置
     * @param $seriesSign
     * @param $methodSign
     * @return array
     */
    public static function getMethodConfig($seriesSign, $methodSign)
    {
        $allConfig = self::getAllMethodConfig($seriesSign);
        return $allConfig[$methodSign] ?? [];
    }
}
