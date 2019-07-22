<?php namespace App\Lib\Game\Method\K3\DXDS;

use App\Lib\Game\Method\K3\Base;

//
class K3HZDXDS extends Base
{
    //大小单双
    //b&s&a&d
    public $all_count =4;
    public static $dxds = array(
        'b' => '大',
        's' => '小',
        'a' => '单',
        'd' => '双',
    );

    // 供测试用 生成随机投注
    public function randomCodes() {
        $rand = rand(1, count(self::$dxds));
        return implode('&', (array)array_rand(array_flip(self::$dxds), $rand));
    }

    public function fromOld($codes) {
        // 0123|0123
        $codes = str_replace(array('0', '1', '2', '3'), array('b', 's', 'a', 'd'), $codes);
        $ex=explode('|', $codes);
        $ex[0]= implode('&', str_split($ex[0]));
        $ex[1]= implode('&', str_split($ex[1]));
        return implode('|', $ex);
    }

    // 格式解析
    public function resolve($codes) {
        return strtr($codes, array_flip(self::$dxds));
    }

    // 还原格式
    public function unresolve($codes) {
        return strtr($codes, self::$dxds);
    }

    public function regexp($sCodes)
    {
        $regexp = '/^([bsad]&){0,3}[bsad]$/';

        if(!preg_match($regexp,$sCodes)) return false;

        $filterArr = self::$dxds;


        $temp = explode('&', $sCodes);
        if(count($temp) != count(array_filter(array_unique($temp),function($v) use($filterArr) {
                return isset($filterArr[$v]);
            }))) return false;

        return !(count($temp) == 0);
    }

    public function count($sCodes)
    {
        $count = count(explode("&", $sCodes));
        return $count;
    }

    public function bingoCode(Array $numbers)
    {
        $b=array_flip([11,12,13,14,15,16,17,18]);
        $s=array_flip([3, 4, 5, 6, 7, 8, 9, 10]);
        $a=array_flip([3,5,7,9,11,13,15,17]);
        $d=array_flip([4,6,8,10,12,14,16,18]);
        $result=[];
        foreach($numbers as $k => $v){
            $tmp=[];
            foreach([$b,$s,$a,$d] as $arr){
                $tmp[]=intval(isset($arr[$v]));
            }
            $result[$k]=$tmp;
        }

        return $result;
    }

    /**
     * 判定 中奖注单
     * 3 - 10 小 11 - 28 大
     * @param $levelId
     * @param $sCodes
     * @param array $numbers
     * @return int
     */
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        // 投注内容
        $aCodes = explode("&", $sCodes);

        // 开奖内容
        $number = array_sum($numbers);

        $bs     = $number > 10 ? 'b' : 's';
        $ad     = $number % 2 == 0 ? 'd' : 'a';

        $arr    = array($bs, $ad);

        $i      = 0;
        $temp   = [];
        foreach ($aCodes as $v1) {
            if(isset($temp[$v1])) continue;
            if (in_array($v1, $arr)) {
                $temp[$v1]=1;
                $i++;
            }
        }

        return $i;
    }
}
