<?php namespace App\Lib\Game\Method\K3\STH;

use App\Lib\Game\Method\K3\Base;
use Illuminate\Support\Facades\Validator;

// 三同号
class STH extends Base
{
    //111&222&333&444&555&666

    public static $filterArr = array('1' => '111','2' => '222','3' => '333','4' => '444','5' => '555','6' => '666');

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $cnt=count(self::$filterArr);
        $rand=rand(1,$cnt);
        return implode('&',(array)array_rand(self::$filterArr,$rand));
    }

    public function fromOld($codes)
    {
        return implode('&',explode('|',$codes));
    }

    //格式解析
    public function resolve($codes)
    {
        return strtr($codes,array_flip(self::$filterArr));
    }

    //还原格式
    public function unresolve($codes)
    {
        return strtr($codes,self::$filterArr);
    }

    public function regexp($sCodes)
    {
        $data['code'] = $sCodes;
        $validator = Validator::make($data, [
            'code' => ['regex:/^(?!\|)(?!.*\|\|$)(?!.*\|$)((([1]{3})|([2]{3})|([3]{3})|([4]{3})|([5]{3})|([6]{3}))\|?)*$/'],//111|222|333|444|555|666 3同号快三
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function count($sCodes)
    {
        return count(explode('&',$sCodes));
    }

    public function bingoCode(Array $numbers)
    {
        //必须有相同号
        $counts=array_count_values($numbers);
        if(count($counts)!=3) return [array_fill(0,count(self::$filterArr),0)];

        $arr=array_keys(self::$filterArr);

        $result=[];
        foreach($arr as $code){
            $result[]=intval(isset($counts[$code]) && $counts[$code]==3);
        }
        return [$result];
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        //三同号单选：当期开奖号码的三个号码相同，且投注号码与当期开奖号码相符，即中奖。
        $aCodes = explode('&',$sCodes);

        //全相等
        if($numbers[0]==$numbers[1] && $numbers[1]==$numbers[2]){
            if(in_array($numbers[0],$aCodes)){
                return 1;
            }
        }
    }

}
