<?php namespace App\Lib\Game\Method\Ssc\DXDS;

use App\Lib\Game\Method\Ssc\Base;
use Illuminate\Support\Facades\Validator;

// 2位大小单双
class H2DXDS extends Base
{
    //大小单双|大小单双
    //b&s&a&d|b&s&a&d
    public $all_count =16;
    public static $dxds = array(
        'b' => '大',
        's' => '小',
        'a' => '单',
        'd' => '双',
    );

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $line=array();
        $rand=rand(1,count(self::$dxds));
        $line[]=implode('&',(array)array_rand(array_flip(self::$dxds),$rand));
        $rand=rand(1,count(self::$dxds));
        $line[]=implode('&',(array)array_rand(array_flip(self::$dxds),$rand));

        return implode('|',$line);
    }

    public function fromOld($codes)
    {
        //0123|0123
        $codes = str_replace(array('0','1','2','3'),array('b','s','a','d'),$codes);
        $ex=explode('|',$codes);
        $ex[0]= implode('&',str_split($ex[0]));
        $ex[1]= implode('&',str_split($ex[1]));
        return implode('|',$ex);
    }

    //格式解析
    public function resolve($codes)
    {
        return strtr($codes,array_flip(self::$dxds));
    }

    //还原格式
    public function unresolve($codes)
    {
        return strtr($codes,self::$dxds);
    }

    public function regexp($sCodes)
    {
        $data['code'] = $sCodes;
        $validator = Validator::make($data, [
            'code' => ['regex:/^((?!\|)(?!.*\|$)(?!.*?\|\|)(?!.*?\&\|)(?!\&)(?!.*\&$)(?!.*?\&\&)(?!.*?\d\d)[0-3&]{0,19}\|?){1,2}$/'],//0&1&2&3|0&1&2&3 大小单双
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function count($sCodes)
    {
        //n1*n2

        $temp = explode("|",$sCodes);
        $n1 = count(explode("&",$temp[0]));
        $n2 = count(explode("&",$temp[1]));

        return $n1 * $n2;
    }

    public function bingoCode(Array $numbers)
    {
        $b=array_flip([5,6,7,8,9]);
        $s=array_flip([0,1,2,3,4]);
        $a=array_flip([1,3,5,7,9]);
        $d=array_flip([0,2,4,6,8]);
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

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        //多注
        $aCodes = explode("|", $sCodes);
        $aTemp1 = explode("&", $aCodes[0]);
        $aTemp2 = explode("&", $aCodes[1]);

        $bs1 = $numbers[0] > 4 ? 'b' : 's';
        $bs2 = $numbers[1] > 4 ? 'b' : 's';
        $ad1 = $numbers[0] % 2 == 0 ? 'd' : 'a';
        $ad2 = $numbers[1] % 2 == 0 ? 'd' : 'a';

        $arr = array(array($bs1, $ad1), array($bs2, $ad2));

        $i=0;
        $temp = [];
        foreach ($aTemp1 as $v1) {
            foreach ($aTemp2 as $v2) {
                if(isset($temp[$v1.'-'.$v2])) {
                    continue;
                }
                if (in_array($v1, $arr[0]) && in_array($v2, $arr[1])) {
                    $temp[$v1.'-'.$v2]=1;
                    $i++;
                }
            }
        }

        return $i;
    }

    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        //b&s&a&d|b&s&a&d

        $codes=explode('|',$sCodes);

        $tmp=[];
        $_codes1=explode('&',$codes[0]);
        $_codes2=explode('&',$codes[1]);
        foreach(array($_codes1,$_codes2) as $k=>$v){
            foreach($v as $vv) {
                switch ($vv) {
                    case 'b':
                        $tmp[$k][5] = 1;
                        $tmp[$k][6] = 1;
                        $tmp[$k][7] = 1;
                        $tmp[$k][8] = 1;
                        $tmp[$k][9] = 1;
                        break;
                    case 's':
                        $tmp[$k][0] = 1;
                        $tmp[$k][1] = 1;
                        $tmp[$k][2] = 1;
                        $tmp[$k][3] = 1;
                        $tmp[$k][4] = 1;
                        break;
                    case 'a':
                        $tmp[$k][1] = 1;
                        $tmp[$k][3] = 1;
                        $tmp[$k][5] = 1;
                        $tmp[$k][7] = 1;
                        $tmp[$k][9] = 1;
                        break;
                    case 'd':
                        $tmp[$k][0] = 1;
                        $tmp[$k][2] = 1;
                        $tmp[$k][4] = 1;
                        $tmp[$k][6] = 1;
                        $tmp[$k][8] = 1;
                        break;
                }
            }
        }

        $str1="'".implode("','",array_keys($tmp[0]))."'";
        $str2="'".implode("','",array_keys($tmp[1]))."'";

        $pos=array_keys(array_intersect($this->lottery->position,$this->levels[1]['position']));
        array_walk($pos,function(&$v){$v++;});

        $script=
            <<<LUA

LUA;

        $max=$lockvalue-$prizes[1];
        $script.= <<<LUA

exists=cmd('exists','{$plan}')

if exists==0 and {$max}<0 then
    do return 0 end
end

ret=cmd('zrangebyscore','{$plan}',{$max},'+inf')

if (#ret==0) then
    do return 1 end
end

codes1={{$str1}}
codes2={{$str2}}
_codes={}
for _,str in pairs(ret) do
    _codes={}
    str:gsub(".",function(c) table.insert(_codes,c) end)
    _codes={_codes[{$pos[0]}],_codes[{$pos[1]}]}

    n1=0
    n2=0
    for _,c in pairs(codes1) do
        for _,_c in pairs(_codes[1]) do
            if c==_c then
                n1=n1+1
            end
        end
    end

    for _,c in pairs(codes2) do
        for _,_c in pairs(_codes[2]) do
            if c==_c then
                n2=n2+1
            end
        end
    end
    if n1*n2>0 then
        do return 0 end
    end
end
do return 1 end

LUA;

        return $script;
    }


    //写入封锁值
    public function lockScript($sCodes,$plan,$prizes)
    {
        //b&s&a&d|b&s&a&d

        $codes=explode('|',$sCodes);
        $aCodes1 = explode('&', $codes[0]);
        $aCodes2 = explode('&', $codes[1]);
        $codes1="'".implode("','",$aCodes1)."'";
        $codes2="'".implode("','",$aCodes2)."'";

        $diff=array_values(array_diff($this->lottery->position,$this->levels[1]['position']));
        $intersect=array_values(array_intersect($this->lottery->position,$this->levels[1]['position']));
        $positions=implode(",",$this->lottery->position);

        $x3=count($this->lottery->position)==3;

        $script='';
        //不同奖级的中奖金额
        $script.= <<<LUA

codes1={{$codes1}}
codes2={{$codes2}}

for _,$intersect[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
    n1 =0;
    for _,v in pairs(codes1) do
        if (v=='b' and $intersect[0]>=5) or (v=='s' and $intersect[0]<5) or (v=='a' and $intersect[0]%2==1) or (v=='d' and $intersect[0]%2==0)  then
            n1=n1+1
        end
    end
for _,$intersect[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
    n2 =0;
    for _,v in pairs(codes2) do
        if (v=='b' and $intersect[1]>=5) or (v=='s' and $intersect[1]<5) or (v=='a' and $intersect[1]%2==1) or (v=='d' and $intersect[1]%2==0)  then
            n2=n2+1
        end
    end

    if n1*n2 >0 then
    times=n1*n2
LUA;
        if($x3){
            $script.= <<<LUA
    for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
    cmd('zincrby','{$plan}',{$prizes[1]}*times,table.concat({{$positions}}))
    end

LUA;
        }else{
            $script.= <<<LUA
    for _,$diff[0] in pairs({0,1,2,3,4,5,6,7,8,9}) do
    for _,$diff[1] in pairs({0,1,2,3,4,5,6,7,8,9}) do
    for _,$diff[2] in pairs({0,1,2,3,4,5,6,7,8,9}) do
        cmd('zincrby','{$plan}',{$prizes[1]}*times,table.concat({{$positions}}))
    end
    end
    end

LUA;
        }
        $script.= <<<LUA

    end
end
end

LUA;

        return $script;
    }

}
