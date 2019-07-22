<?php namespace App\Lib\Game\Method\K3\EBTH;

use App\Lib\Game\Method\K3\Base;

// 二不同号
class EBTH extends Base
{
    //1&2&3&4&5&6

    public static $filterArr = array('1' => 1,'2' => 1,'3' => 1,'4' => 1,'5' => 1,'6' => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $cnt=count(self::$filterArr);
        $rand=rand(2,$cnt);
        return implode('&',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        return implode('&',str_split($codes));
    }

    public function regexp($sCodes)
    {
        if (!preg_match("/^(([1-6]&){0,5}[1-6])$/", $sCodes)) {
            return false;
        }

        $temp=explode('&',$sCodes);
        if(count($temp) != count(array_unique($temp))){
            return 0;
        }

        $aCode = explode('&',$sCodes);

        $filterArr = self::$filterArr;
        $nums = count(array_filter($aCode, function($v) use ($filterArr) {
            return isset($filterArr[$v]);
        }));

        if($nums==0){
            return false;
        }

        if($nums != count($aCode)) {
            return false;
        }

        return true;
    }

    public function count($sCodes)
    {
        //C(n,2)
        $temp = explode('&',$sCodes);
        $n = count($temp);
        return $this->getCombinCount($n,2);
    }

    public function bingoCode(Array $numbers)
    {
        $result=[];
        $arr=array_keys(self::$filterArr);
        foreach($numbers as $pos=>$code){
            $tmp=[];
            foreach($arr as $_code){
                $tmp[]=intval($code==$_code);
            }
            $result[$pos]=$tmp;
        }

        return $result;
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        //二不同号投注：当期开奖号码中有两个号码不相同，且投注号码中的两个不同号码与当期开奖号码中的两个不同号码相符，即中奖。
        //两个非重复的
        $temp=array_count_values($numbers);

        if(count($temp)==1){
            //排除豹子
            return 0;
        }

        $i=0;

        $arrs = $this->getCombination(explode('&',$sCodes), 2);
        foreach ($arrs as $str) {
            $t=explode(' ',$str);
            if(isset($temp[$t[0]], $temp[$t[1]])){
                $i++;
            }
        }

        return $i;
    }
}
