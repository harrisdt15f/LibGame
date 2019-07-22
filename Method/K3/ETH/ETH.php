<?php namespace App\Lib\Game\Method\K3\ETH;

use App\Lib\Game\Method\K3\Base;

// 二同号
class ETH extends Base
{
    //1&2&3&4&5&6|1&2&3&4&5&6

    public static $filterArr = array('1' => '11','2' => '22','3' => '33','4' => '44','5' => '55','6' => '66');

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $cnt=count(self::$filterArr);
        $rand=rand(1,$cnt-1);
        $rand2=$cnt-$rand;

        $temp=(array)array_rand(self::$filterArr,$rand);
        $_arr2=array_diff(array_keys(self::$filterArr),$temp);
        $arr[]=implode('&',$temp);
        $arr[]=implode('&',(array)array_rand(array_flip($_arr2),$rand2));

        return implode('|',$arr);
    }

    public function fromOld($codes)
    {
        return implode('|',array_map(function($v){
            return implode('&',str_split($v));
        },explode('|',$codes)));
    }

    //格式解析
    public function resolve($codes)
    {
        $temp=explode('|',$codes);
        $temp[0]=strtr($temp[0],array_flip(self::$filterArr));
        return implode('|',$temp);
    }

    //还原格式
    public function unresolve($codes)
    {
        $temp=explode('|',$codes);
        $temp[0]=strtr($temp[0],self::$filterArr);
        return implode('|',$temp);
    }

    public function regexp($sCodes)
    {
        if (!preg_match("/^(([1-6]&){0,5}[1-6])\|(([1-6]&){0,5}[1-6])$/", $sCodes)) {
            return false;
        }

        $filterArr = self::$filterArr;

        $aTmp = explode('|', $sCodes);
        $aDan = explode('&', $aTmp[0]);
        if (count($aDan) != count(array_filter(array_unique($aDan),function($v) use($filterArr) {
                return isset($filterArr[$v]);
            }))) { //不能有重复的号码
            return false;
        }
        $aTuo = explode('&', $aTmp[1]);
        if (count($aTuo) != count(array_filter(array_unique($aTuo),function($v) use($filterArr) {
                return isset($filterArr[$v]);
            }))) { //不能有重复的号码
            return false;
        }

        //有重复的
        if (count(array_intersect($aDan, $aTuo)) > 0) {
            return false;
        }

        if(count($aDan)==0 || count($aTuo)==0){
            return false;
        }

        return true;
    }

    public function count($sCodes)
    {
        $aTmp = explode('|', $sCodes);
        $aDan = explode('&', $aTmp[0]);
        $aTuo = explode('&', $aTmp[1]);
        return count($aTuo) *  count($aDan);
    }

    public function bingoCode(Array $numbers)
    {
        //必须有相同号
        $counts=array_count_values($numbers);

        $tmp=array_fill(0,count(self::$filterArr),0);
        if(count($counts)!=2) return [$tmp,$tmp];

        $arr=array_keys(self::$filterArr);

        $result=[];
        //同号
        $t=[];
        foreach($arr as $code){
            $t[]=intval(isset($counts[$code]) && $counts[$code]==2);
        }

        $result[]=$t;
        //不同号
        $bt=[];
        foreach($arr as $code){
            $bt[]=intval(isset($counts[$code]) && $counts[$code]==1);
        }
        $result[]=$bt;

        return $result;
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        //二同号单选：当期开奖号码中有两个号码相同，且投注号码与当期开奖号码中两个相同号码和一个不同号码分别相符，即中奖。
        $str = $this->strOrder(implode('',$numbers));

        $aTmp = explode('|', $sCodes);
        $aDan = explode('&', $aTmp[0]);
        $aTuo = explode('&', $aTmp[1]);

        foreach($aDan as $d){
            foreach($aTuo as $t){
                if($this->strOrder($d.''.$d.''.$t) == $str){
                    return 1;
                }
            }
        }
    }
}
