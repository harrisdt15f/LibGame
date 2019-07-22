<?php namespace App\Lib\Game\Method\K3\HZ;

use App\Lib\Game\Method\K3\Base;

//快三和值
class KSHZ extends Base
{
    //1&2&3&4&5&6&27

    public static $filterArr = array(3=>1,4=>1,5=>1,6=>1,7=>1,8=>1,9=>1,10=>1,11=>1,12=>1,13=>1,14=>1,15=>1,16=>1,17=>1,18=>1);
    /*
        和值:3,18:320.00元 (1注)
        和值:4,17:110.00元 (3注)
        和值:5,16:55.00元 (6注)
        和值:6,15:30.00元 (10注)
        和值:7,14:20.00元 (15注)
        和值:8,13:14.50元 (21注)
        和值:9,12:13.00元 (25注)
        和值:10,11:11.50元 (27注)
     */

    public static $ls = array(
        "1"=>array(3,18),
        "2"=>array(4,17),
        "3"=>array(5,16),
        "4"=>array(6,15),
        "5"=>array(7,14),
        "6"=>array(8,13),
        "7"=>array(9,12),
        "8"=>array(10,11),
    );

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand = rand(1,count(self::$filterArr));
        return implode('&',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        return implode('&',explode('|',$codes));
    }
    public function regexp($sCodes)
    {
        //去重
        $t=explode("&",$sCodes);
        $temp =array_unique($t);
        $arr = self::$filterArr;

        $temp = array_filter($temp,function($v) use ($arr) {
            return isset($arr[$v]);
        });

        if(count($temp)==0){
            return false;
        }

        return count($temp) == count($t);
    }

    public function count($sCodes)
    {
        //枚举之和
        $n = 0;
        $temp = explode('&',$sCodes);
        foreach($temp as $c){
            $n += self::$filterArr[$c];
        }

        return $n;
    }

    public function bingoCode(Array $numbers)
    {
        $val=array_sum($numbers);

        $arr=array_keys(self::$filterArr);

        $result=[];
        foreach($arr as $code){
            $result[]=intval($code==$val);
        }
        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        //和值：投注号码与当期开奖号码的三个号码的和值相符，即中奖。
        $val = array_sum($numbers);

        $aCodes = explode('&', $sCodes);

        $l = self::$ls[$levelId];
        if(in_array($val,$l)){
            foreach ($aCodes as $code) {
                if ($code == $val) {
                    return 1;
                }
            }
        }
    }

}
