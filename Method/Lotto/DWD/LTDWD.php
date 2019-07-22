<?php namespace App\Lib\Game\Method\Lotto\DWD;

use App\Lib\Game\Method\Lotto\Base;

// DWD 需分拆
class LTDWD
{
    public $positionsTpl = array('1' => '第一位', '2' => '第二位', '3' => '第三位');

    public $supportExpand = true;

    public function bingoCode(Array $numbers)
    {
        $result = [];
        $arr = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11'];

        foreach ($numbers as $pos => $code) {
            $tmp = [];
            foreach ($arr as $_code) {
                $tmp[] = intval($code == $_code);
            }
            $result[$pos] = $tmp;
        }

        return $result;
    }

    // 供测试用 生成随机投注
    public function randomCodes(&$poss = array())
    {
        $positions = array('b' => '', 's' => '', 'g' => '');
        if (!$this->pos) {
            //集合
            $pos = (array)array_rand($positions, rand(1, count($positions)));
        } else {
            $pos = str_split($this->pos);
        }

        //按为生成随机
        foreach ($pos as $k => $v) {
            $positions[$v] = parent::randomCodes();
        }

        return implode('|', $positions);
    }

    public function expand($sCodes, $pos = null)
    {
        $result = [];
        $aCodes = explode('|', $sCodes);
        foreach ($aCodes as $index => $code) {
            if (trim($code) === '') continue;
            switch ($index) {
                case 0:
                    $methodId = $this->id . "_1";
                    break;
                case 1:
                    $methodId = $this->id . "_2";
                    break;
                case 2:
                    $methodId = $this->id . "_3";
                    break;
                default:
                    $methodId = "";
            }
            if (!$methodId) continue;

            $result[] = array(
                'method_id' => $methodId,
                'codes'     => $code,
                'count'     => count(explode('&', $code)),
            );
        }
        return $result;
    }
}
