<?php

namespace App\Lib\Game;

class Math
{

    public static function combin($iBase, $iChoosed)
    {
        if ($iBase < $iChoosed) {
            return 0;
        }
        if ($iBase == $iChoosed) {
            return 1;
        }
        if (($iEqual = $iBase - $iChoosed) < $iChoosed) {
            return self::combin($iBase, $iEqual);
        }
        return self::permut($iBase, $iChoosed) / self::factorial($iChoosed);
    }

    public static function permut($iBase, $iChoosed)
    {
        if ($iBase < $iChoosed) {
            return 0;
        }
        for ($i = 0, $p = 1; $i < $iChoosed; $p *= ($iBase - $i++)) {
        }
        return $p;
    }

    public static function factorial($iNum)
    {
        for ($f = 1, $i = 2; $i <= $iNum; $f *= $i++) {
        }
        return $f;
    }

    /**
     * n个数里取m个数的全组合
     * @param $arr
     * @param $m
     * @return array
     */
    public static function getCombinationToString($arr, $m): array
    {
        if (!is_array($arr) || count($arr) < $m) {
            return [];
        }
        $result = array();
        if ($m == 1) {
            return $arr;
        }
        if ($m == count($arr)) {
            $result[] = implode(',', $arr);
            return $result;
        }
        $temp_firstelement = $arr[0];
        unset($arr[0]);
        $arr = array_values($arr);
        $temp_list1 = self::getCombinationToString($arr, $m - 1);
        foreach ($temp_list1 as $s) {
            $s = $temp_firstelement.','.$s;
            $result[] = $s;
        }
        unset($temp_list1);
        $temp_list2 = self::getCombinationToString($arr, $m);
        foreach ($temp_list2 as $s) {
            $result[] = $s;
        }
        unset($temp_list2);
        return $result;
    }

    public static function getCombin4Renxun($n = 2, $aT = [1, 1, 1, 1, 1])
    {
        $sum = 0;
        switch ($n) {
            case 2:
                for ($i = 0; $i < 5 - 1; $i++) {
                    for ($j = $i + 1; $j < 5; $j++) {
                        $sum += $aT[$i] * $aT[$j];
                    }
                }
                break;
            case 3:
                for ($i = 0; $i < 5 - 2; $i++) {
                    for ($j = $i + 1; $j < 5 - 1; $j++) {
                        for ($k = $j + 1; $k < 5; $k++) {
                            $sum += $aT[$i] * $aT[$j] * $aT[$k];
                        }
                    }
                }
                break;
            case 4:
                for ($i = 0; $i < 5 - 3; $i++) {
                    for ($j = $i + 1; $j < 5 - 2; $j++) {
                        for ($k = $j + 1; $k < 5 - 1; $k++) {
                            for ($l = $k + 1; $l < 5; $l++) {
                                $sum += $aT[$i] * $aT[$j] * $aT[$k] * $aT[$l];
                            }
                        }
                    }
                }
                break;
            case 5:
                for ($i = 0; $i < 5 - 4; $i++) {
                    for ($j = $i + 1; $j < 5 - 3; $j++) {
                        for ($k = $j + 1; $k < 5 - 2; $k++) {
                            for ($l = $k + 1; $l < 5 - 1; $l++) {
                                for ($g = $l + 1; $g < 5; $g++) {
                                    $sum += $aT[$i] * $aT[$j] * $aT[$k] * $aT[$l] * $aT[$g];
                                }
                            }
                        }
                    }
                }
                break;
        }
        return $sum;
    }

}