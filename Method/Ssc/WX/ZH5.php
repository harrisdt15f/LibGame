<?php  namespace App\Lib\Game\Method\Ssc\WX;
use App\Lib\Game\Method\Ssc\Base;

class ZH5 extends Base
{
    // 0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
    public $all_count =500000;
    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $arr=[];
        $rand=rand(1,10);
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));
        $rand=rand(1,10);
        $arr[]= implode('&',(array)array_rand(self::$filterArr,$rand));

        return implode('|',$arr);
    }

    public function regexp($sCodes)
    {
        $regexp = '/^(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])\|(([0-9]&){0,9}[0-9])$/';
        if( !preg_match($regexp,$sCodes) ) return false;

        $filterArr = self::$filterArr;

        //去重
        $sCodes = explode("|", $sCodes);
        foreach($sCodes as $codes){
            $temp = explode('&',$codes);
            if(count($temp) != count(array_filter(array_unique($temp),function($v) use($filterArr) {
                    return isset($filterArr[$v]);
                }))) return false;

            if(count($temp)==0){
                return false;
            }
        }

        return true;
    }

    public function count($sCodes)
    {
        //n1*n2*n3*n4*n5*5
        $cnt = 1;
        $temp = explode('|',$sCodes);
        foreach($temp as $c){
            $cnt *= count(explode('&',$c));
        }

        $cnt *= 5;

        return $cnt;
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
        $aCodes = explode('|', $sCodes);

        if ($levelId == '1') {
            $preg = "|[" . str_replace('&', '', $aCodes[0]) . "][" . str_replace('&', '', $aCodes[1]) . "][" . str_replace('&', '', $aCodes[2]) . "][" . str_replace('&', '', $aCodes[3]) . "][" . str_replace('&', '', $aCodes[4]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                return 1;
            }
        } elseif ($levelId == '2') {
            $preg = "|[" . str_replace('&', '', $aCodes[1]) . "][" . str_replace('&', '', $aCodes[2]) . "][" . str_replace('&', '', $aCodes[3]) . "][" . str_replace('&', '', $aCodes[4]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                $times = count(explode('&',$aCodes[0]));
                return $times;
            }
        } elseif ($levelId == '3') {
            $preg = "|[" . str_replace('&', '', $aCodes[2]) . "][" . str_replace('&', '', $aCodes[3]) . "][" . str_replace('&', '', $aCodes[4]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                $times = count(explode('&',$aCodes[0])) * count(explode('&',$aCodes[1])) ;
                return $times;
            }
        } elseif ($levelId == '4') {
            $preg = "|[" . str_replace('&', '', $aCodes[3]) . "][" . str_replace('&', '', $aCodes[4]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                $times = count(explode('&',$aCodes[0])) * count(explode('&',$aCodes[1])) * count(explode('&',$aCodes[2])) ;
                return $times;
            }
        } elseif ($levelId == '5') {
            $preg = "|[" . str_replace('&', '', $aCodes[4]) . "]|";
            if (preg_match($preg, implode("", $numbers))) {
                $times = count(explode('&',$aCodes[0])) * count(explode('&',$aCodes[1])) * count(explode('&',$aCodes[2])) * count(explode('&',$aCodes[3])) ;
                return $times;
            }
        }

    }


    //检查封锁
    public function tryLockScript($sCodes,$plan,$prizes,$lockvalue)
    {
        $codes=explode('|',$sCodes);
        $tmp=[0,1,2,3,4,5,6,7,8,9];
        $diffs=[];
        $pcnt=[];
        foreach($codes as $k=>&$code){
            $code=str_replace('&',',',$code);
            $diffs[$k]=implode(',',array_diff($tmp,explode(',',$code)));

            $pcnt[$k]=count(explode(',',$code));
        }

        $times =$pcnt[0]*$pcnt[1]*$pcnt[2]*$pcnt[3] ;
        $p5=$times*$prizes[5];

        $times = $pcnt[0]*$pcnt[1]*$pcnt[2];
        $p4=$times*$prizes[4];

        $times = $pcnt[0]*$pcnt[1];
        $p3=$times*$prizes[3];

        $times = $pcnt[0];
        $p2=$times*$prizes[2];

        $times=1;
        $p1=$times*$prizes[1];

        $prizes[1]=$p1;
        $prizes[2]=$p2;
        $prizes[3]=$p3;
        $prizes[4]=$p4;
        $prizes[5]=$p5;

        $script=
            <<<LUA

LUA;
        //一等奖
        $max1=$lockvalue-$prizes[1];
        $max2=$lockvalue-$prizes[2];
        $max3=$lockvalue-$prizes[3];
        $max4=$lockvalue-$prizes[4];
        $max5=$lockvalue-$prizes[5];

        $script.= <<<LUA

exists=cmd('exists','{$plan}')

if exists==0 and {$max1}<0 then
    do return 0 end
end

-- 一等奖
ret=cmd('zrangebyscore','{$plan}',{$max1},'+inf')
if (#ret==0) then
    do return 1 end
end

a={{$codes[0]}}
b={{$codes[1]}}
c={{$codes[2]}}
d={{$codes[3]}}
e={{$codes[4]}}
for _,str in pairs(ret) do

    for _,i in pairs(a) do
    for _,j in pairs(b) do
    for _,k in pairs(c) do
    for _,l in pairs(d) do
    for _,m in pairs(e) do
    if (str==table.concat({i,j,k,l,m})) then
        do return 0 end
    end
    end
    end
    end
    end
    end
end

-- 二等奖
ret=cmd('zrangebyscore','{$plan}',{$max2},'+inf')
if (#ret==0) then
    do return 1 end
end

a={{$diffs[0]}}
for _,str in pairs(ret) do

    for _,i in pairs(a) do
    for _,j in pairs(b) do
    for _,k in pairs(c) do
    for _,l in pairs(d) do
    for _,m in pairs(e) do
    if (str==table.concat({i,j,k,l,m})) then
        do return 0 end
    end
    end
    end
    end
    end
    end
end


-- 三等奖
ret=cmd('zrangebyscore','{$plan}',{$max3},'+inf')
if (#ret==0) then
    do return 1 end
end

b={{$diffs[1]}}
for _,str in pairs(ret) do

    for _,i in pairs(a) do
    for _,j in pairs(b) do
    for _,k in pairs(c) do
    for _,l in pairs(d) do
    for _,m in pairs(e) do
        if (str==table.concat({i,j,k,l,m})) then
            do return 0 end
        end
    end
    end
    end
    end
    end
end


-- 四等奖
ret=cmd('zrangebyscore','{$plan}',{$max4},'+inf')
if (#ret==0) then
    do return 1 end
end

c={{$diffs[2]}}
for _,str in pairs(ret) do

    for _,i in pairs(a) do
    for _,j in pairs(b) do
    for _,k in pairs(c) do
    for _,l in pairs(d) do
    for _,m in pairs(e) do
        if (str==table.concat({i,j,k,l,m})) then
            do return 0 end
        end
    end
    end
    end
    end
    end
end


-- 五等奖
ret=cmd('zrangebyscore','{$plan}',{$max5},'+inf')
if (#ret==0) then
    do return 1 end
end

d={{$diffs[3]}}
for _,str in pairs(ret) do
    for _,i in pairs(a) do
    for _,j in pairs(b) do
    for _,k in pairs(c) do
    for _,l in pairs(d) do
    for _,m in pairs(e) do
        if (str==table.concat({i,j,k,l,m})) then
            do return 0 end
        end
    end
    end
    end
    end
    end
end

do return 1 end

LUA;

        return $script;
    }

    //写入封锁值
    public function lockScript($sCodes,$plan,$prizes)
    {
        $tmp=[0,1,2,3,4,5,6,7,8,9];
        $codes=explode('|',$sCodes);
        $diffs=[];
        $pcnt=[];
        foreach($codes as $k=>&$code){
            $_code=str_replace('&',',',$code);
            $code=$_code;
            $diffs[$k]=implode(',',array_diff($tmp,explode(',',$_code)));

            $pcnt[$k]=count(explode(',',$code));
        }

        $times =$pcnt[0]*$pcnt[1]*$pcnt[2]*$pcnt[3] ;
        $p5=$times*$prizes[5];

        $times = $pcnt[0]*$pcnt[1]*$pcnt[2];
        $p4=$times*$prizes[4];

        $times = $pcnt[0]*$pcnt[1];
        $p3=$times*$prizes[3];

        $times = $pcnt[0];
        $p2=$times*$prizes[2];

        $times=1;
        $p1=$times*$prizes[1];

        $prizes[1]=$p1;
        $prizes[2]=$p2;
        $prizes[3]=$p3;
        $prizes[4]=$p4;
        $prizes[5]=$p5;

        //不同奖级的中奖金额
        $script= <<<LUA

x={0,1,2,3,4,5,6,7,8,9}
a={{$codes[0]}}
b={{$codes[1]}}
c={{$codes[2]}}
d={{$codes[3]}}
e={{$codes[4]}}

-- 一等奖
for _,i in pairs(a) do
for _,j in pairs(b) do
for _,k in pairs(c) do
for _,l in pairs(d) do
for _,m in pairs(e) do
    cmd('zincrby','{$plan}',{$prizes[1]},table.concat({i,j,k,l,m}, ''))
end
end
end
end
end

-- 二等奖
for _,i in pairs(x) do
for _,j in pairs(b) do
for _,k in pairs(c) do
for _,l in pairs(d) do
for _,m in pairs(e) do
    cmd('zincrby','{$plan}',{$prizes[2]},table.concat({i,j,k,l,m}, ''))
end
end
end
end
end

-- 三等奖
for _,i in pairs(x) do
for _,j in pairs(x) do
for _,k in pairs(c) do
for _,l in pairs(d) do
for _,m in pairs(e) do
    cmd('zincrby','{$plan}',{$prizes[3]},table.concat({i,j,k,l,m}, ''))
end
end
end
end
end

-- 四等奖
for _,i in pairs(x) do
for _,j in pairs(x) do
for _,k in pairs(x) do
for _,l in pairs(d) do
for _,m in pairs(e) do
    cmd('zincrby','{$plan}',{$prizes[4]},table.concat({i,j,k,l,m}, ''))
end
end
end
end
end

-- 五等奖
for _,i in pairs(x) do
for _,j in pairs(x) do
for _,k in pairs(x) do
for _,l in pairs(x) do
for _,m in pairs(e) do
    cmd('zincrby','{$plan}',{$prizes[5]},table.concat({i,j,k,l,m}, ''))
end
end
end
end
end

LUA;
        return $script;
    }
}
