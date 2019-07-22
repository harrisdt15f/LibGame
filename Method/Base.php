<?php
namespace App\Lib\Game\Method;

abstract class Base
{
    public $id;
    public $pos = '';

    public $pattern;
    public $config;

    public $lottery;

    public $lock = true;

    public static $_abc = array(
        '01' => 'a',
        '02' => 'b',
        '03' => 'c',
        '04' => 'd',
        '05' => 'e',
        '06' => 'f',
        '07' => 'g',
        '08' => 'h',
        '09' => 'i',
        '10' => 'j',
        '11' => 'k',
    );

    // 构造函数
    public function __construct($id, $pattern, $config)
    {
        $this->id = $id;
        $this->pattern = $pattern;

        // 补全
        $config['id'] = $id;
        $config['pattern'] = $pattern;
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function toArray()
    {
        return $this->config;
    }

    // 魔术方法
    public function __get($name)
    {
        return $this->config[$name] ?? null;
    }

    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    // 解析加密数据
    public function _parse64($str, $_n = 5)
    {
        $temp = [];
        try {
            $str2 = gzdecode(base64_decode($str));
        } catch (\Exception $e) {
            $str2 = '';
        }

        if (!$str2) {
            return $str;
        }

        if (preg_match_all('/[smn]\d+/', $str2, $matchs)) {
            if (!empty($matchs[0])) {
                foreach ($matchs[0] as $v) {
                    if ($v[0] == 's') {
                        $temp[] = $v;
                    } elseif ($v[0] == 'm') {
                        $temp[] = $v;
                    } elseif ($v[0] == 'n') {
                        $temp[] = $v;
                    }
                }
            }
        }

        $codes = [];
        $start = '';

        foreach ($temp as $v) {
            if ($v[0] == 's') {
                list($_s, $n) = explode('s', $v);
                $codes[] = $this->_psInt($n, $_n);
                $start = $n;
            } elseif ($v[0] == 'm') {
                list($_s, $n) = explode('m', $v);
                for ($i = 0; $i < $n; $i++) {
                    $start++;
                    $codes[] = $this->_psInt($start, $_n);
                }
            } elseif ($v[0] == 'n') {
                list($_s, $n) = explode('n', $v);
                $start += $n;
                $codes[] = $this->_psInt($start, $_n);
            }
        }

        return $codes;
    }

    public function _psInt($i, $n = 5)
    {
        if ($n == 5) {
            if ($i < 10) {
                return '0000' . $i;
            } elseif ($i < 100) {
                return '000' . $i;
            } elseif ($i < 1000) {
                return '00' . $i;
            } elseif ($i < 10000) {
                return '0' . $i;
            }

            return $i;
        } elseif ($n == 4) {
            if ($i < 10) {
                return '000' . $i;
            } elseif ($i < 100) {
                return '00' . $i;
            } elseif ($i < 1000) {
                return '0' . $i;
            }

            return $i;
        } elseif ($n == 3) {
            if ($i < 10) {
                return '00' . $i;
            } elseif ($i < 100) {
                return '0' . $i;
            }

            return $i;
        } else {
            return $i;
        }
    }

    public function parse64($codes)
    {
        return $codes;
    }

    public function encode64($codes)
    {
        return $codes;
    }

    public function _encode64($arr)
    {
        if (!is_array($arr)) {
            $arr = explode(',', $arr);
        }

        $chartData = [];
        $m = $n = 0;
        $last = (int) $arr[0];
        $start = $last;
        for ($i = 1, $iMax = count($arr); $i < $iMax; $i++) {
            $current = (int) $arr[$i];
            if ($current == $last + 1) {
                $m++;
            } else {
                $n = $current - $last;
                if ($m > 0) {
                    $chartData[] = 'm'. $m;
                }

                if ($n > 0) {
                    $chartData[] = 'n'. $n;
                }

                $m = 0;
            }
            $last = $current;
        }
        if ($m > 0) {
            $chartData[] = 'm'. $m;
        }

        $string = 's' . $start . implode('', $chartData);
        return 'base64:' . base64_encode(gzencode($string));
    }

    // 必须继承
    public function regexp($codes)
    {
        return false;
    }

    // 必须继承
    public function count($sCodes)
    {
        return 0;
    }

    // 必须继承
    public function assertLevel($levelId, $sCodes, array $numbers)
    {
        return 0;
    }

    //是否复式
    public function isMulti()
    {
        return (strtoupper(substr($this->id, strlen($this->id) - 2, 2)) == '_S') ? false : true;
    }

    // 利润率
    public function profit()
    {
        // 1-(转直*中奖金额/全包金额)
        $total = $this->total;
        $levels = $this->getLevels();
        $level = array_shift($levels); //取最大奖级
        $probability = ($level['count'] * $level['prize']) / ($total * 2);
        $profit = number_format(1 - $probability, 3, '.', '');
        return real2point($profit);
    }

    public function maxprize($dypoint = null)
    {
        $tempVar = $this->getLevels();
        if (isset($this->jzjd) && $this->jzjd) {
            $max = 0;
            foreach ($tempVar as $level) {
                if ($dypoint !== null) {
                    $max += $this->dyprize($level, $dypoint);
                } else {
                    $max += $level['prize'];
                }
            }
            return $max;
        } else {
            // 高奖金在前面
            $level = array_shift($tempVar);
            if ($dypoint != null) {
                $max = $this->dyprize($level, $dypoint);
            } else {
                $max = $level['prize'];
            }

            return $max;
        }
    }

    public function dyprize($level, $fPoint)
    {
        $fPrize = +$level['prize'];
        $iTotalMoney = $this->total * 2;
        $iCount = $level['count'];
        $fLastPrize = floor(round(($fPrize + $iTotalMoney / $iCount * $fPoint) * 100, 1)) / 100; // 取两位小数
        return round($fLastPrize, 2); // 精确到两位小数
    }

    //获得注数
    public function getCount($sCode)
    {
        return $this->count($sCode);
    }

    // 检查是否全包投注方式
    public function isAllIn($count, $rate = 1)
    {
        if ($this->all_count > 0) {
            if ($count / $this->all_count >= $rate) {
                return true;
            }
        }

        return false;
    }

    public function lockLater($issueno, $sCodes, $dypoint, $times, $mode, $force = false)
    {
        if (!$force && !$this->lottery->selfopen) {
            if (!$this->lock || !$this->lottery->lock) {
                // 不要求封锁
                return '';
            }
        }

        $plan = $this->lottery->id . '_' . $issueno;
        $prizes = $this->getLockPrizes($dypoint, $times, $mode);
        $script = $this->lockScript($sCodes, $plan, $prizes);

        return " \n " . $script . " \n";
    }

    public function lockLater2($issueno, $sCodes, $dypoint, $times, $mode, $force = false)
    {
        $plan = $this->lottery->id . '_' . $issueno;
        $prizes = $this->getLockPrizes($dypoint, $times, $mode);
        $script = $this->lockScript($sCodes, $plan, $prizes);

        return " \n " . $script . " \n";
    }

    public function tryLockScript($sCodes, $plan, $prizes, $lockvalue)
    {
        return '';
    }

    public function lockScript($sCodes, $plan, $prizes)
    {
        return '';
    }

    public function expand($sCodes, $pos = null)
    {
        return [];
    }

    // 展开成多个玩法 比如dwd@wq   rzx3@wqb
    public function _expand()
    {
        if (!$this->supportExpand || empty($this->positionsTpl) || empty($this->expands)) {
            return array($this->id => $this);
        }

        $methods = [];
        $position = array_keys($this->positionsTpl);
        $num = $this->expands['num'];
        $poss = $this->getCombination($position, $num);
        $methodid = $this->id;
        foreach ($poss as $pos) {
            $pos = explode(' ', $pos);
            // 做好排序 wqb这样的顺序
            $pos = implode('', array_keys(array_intersect_key($this->positionsTpl, array_flip($pos))));
            $_methodid = $methodid . '@' . $pos;
            $methods[$_methodid] = $this->lottery->method($_methodid);
        }

        return $methods;
    }

    public function renew($pos)
    {
        if (!$this->supportExpand || !$pos) {
            return false;
        }

        if (empty($this->positionsTpl) || empty($this->expands)) {
            return false;
        }

        $this->id .= $pos.'@';
        $this->pos = $pos;

        //改变配置
        $config = $this->config;
        $str = implode('', array_values(array_intersect_key($this->positionsTpl, array_flip(str_split($pos)))));
        $config['name'] = str_replace('{str}', $str, $this->expands['name']);
        foreach ($config['levels'] as &$level) {
            $level['position'] = str_split($pos);
        }
        unset($config['expands']);
        $this->config = $config;

        //已展开的标记为不能再展开
        if (trim($pos) !== '') {
            $this->supportExpand = false;
        }
    }

    public function checkPos($pos)
    {
        return true;
    }

    public function getLockPrizes($dypoint, $times, $mode)
    {
        $prizes = array();
        $levels = $this->getLevels();

        foreach ($levels as $k => $level) {
            if ($dypoint !== null) {
                $max = +$this->dyprize($level, $dypoint);
            } else {
                $max = +$level['prize'];
            }

            $prizes[$k] = $max * $times * $mode;
        }

        return $prizes;
    }

    // 检验格式是否合法
    public function checkRegexp($sCode)
    {
        return $this->regexp($sCode);
    }

    public function assert($sCodes, array $aOpenCode)
    {
        $results = [];
        $levels = $this->getLevels();
        foreach ($levels as $levelId => $level) {
            if (!$levelId) {
                continue;
            }

            info($aOpenCode);
            $aOpenCode = array_values(array_intersect_key($aOpenCode, array_flip($level['position'])));
            info($aOpenCode);
            $num = $this->assertLevel($levelId, $sCodes, $aOpenCode);
            if ($num > 0) {
                //中奖
                $results[$levelId] = $num;
                if (!(isset($this->jzjd) && $this->jzjd)) {
                    //非兼中兼得?
                    break;
                }
            }
        }

        return $results;
    }

    public function getLevels()
    {
        return $this->config['levels'];
    }

    // 冷热 & 遗漏
    public function getHotCodes(array $aOpenCodes, $omission = false)
    {
        if ($this->supportExpand) {
            //对集合型玩法的处理
            $exists = array_flip(array_keys($this->positionsTpl));
        } else {
            $levels = $this->getLevels();
            $exists = [];
            foreach ($levels as $levelId => $level) {
                if (!$levelId) {
                    continue;
                }

                foreach ($level['position'] as $pos) {
                    $exists[$pos] = 1;
                }
            }
        }

        $results = [];
        foreach ($aOpenCodes as $aOpencode) {
            $numbers = array_values(array_intersect_key($aOpencode, $exists));

            $result = $this->bingoCode($numbers);
            if (empty($result)) {
                continue;
            }

            if (empty($results)) {
                $results = $result;
            } else {
                if (!$omission) {
                    //冷热
                    $results = array_map(function ($data1, $data2) {
                        return array_map(function ($v1, $v2) {
                            return $v1 + $v2;
                        }, $data1, $data2);
                    }, $results, (array) $result);
                } else {
                    //遗漏
                    $results = array_map(function ($data1, $data2) {
                        return array_map(function ($v1, $v2) {
                            if ($v2 == 1) {
                                return 0;
                            }

                            return $v1 + 1;
                        }, $data1, $data2);
                    }, $results, (array) $result);
                }
            }
        }

        return $results;
    }

    public function bingoCode(array $numbers)
    {
        return [];
    }

    // 将lt01 转成 单字符 a b c,以便跟数字形统一逻辑
    public function convertLtCodes($lt, $encode = true)
    {
        $keys = array_keys(self::$_abc);
        $values = array_values(self::$_abc);

        if ($encode) {
            if (is_array($lt)) {
                foreach ($lt as &$l) {
                    $l = str_replace($keys, $values, $l);
                }

            } else {
                $lt = str_replace($keys, $values, $lt);
            }
        } else {
            if (is_array($lt)) {
                foreach ($lt as &$l) {
                    $l = str_replace($values, $keys, $l);
                }
            } else {
                $lt = str_replace($values, $keys, $lt);
            }
        }

        return $lt;
    }

    //格式解析
    public function resolve($codes)
    {
        return $codes;
    }

    //格式还原
    public function unresolve($codes)
    {
        return $codes;
    }

    //显示给前端用
    public function format($codes)
    {
        if ($this->isMulti()) {
            if (in_array($this->pattern, array('digital', 'digital3', 'p3p5'))) {
                if (strpos($codes, '|') !== false || $this->method == 'DWD') {
                    $codes = str_replace('&', '', $codes);
                } else {
                    $codes = str_replace('&', ',', $codes);
                }
            } elseif ($this->pattern == 'lotto') {
                if (strpos($codes, '|') !== false || $this->method == 'LTDWD') {
                    $codes = str_replace('&', ' ', $codes);
                } else {
                    $codes = str_replace('&', ',', $codes);
                }
            } elseif ($this->pattern == 'pk10') {
                if (strpos($codes, '|') !== false || $this->method == 'DWD') {
                    $codes = str_replace('&', ' ', $codes);
                } else {
                    $codes = str_replace('&', ',', $codes);
                }
            } elseif ($this->pattern == 'ks') {
                if (strpos($codes, '|') !== false) {
                    $codes = str_replace('&', '', $codes);
                } else {
                    $codes = str_replace('&', ',', $codes);
                }
            }
        }
        return $codes;
    }

    /**
     * T::格式化开奖号码 对应到位置
     * @param $sOpenCodes
     * @return array
     */
    public function formatCode($sOpenCodes)
    {
        return array_combine($this->config['position'], $sOpenCodes);
    }

    public function openIgnore($openCode)
    {
        return false;
    }

    public function randomCodes()
    {
        return '';
    }

    //从老系统的投注转化做测试
    public function fromOld($codes)
    {
        if (strpos($codes, '|') !== false) {
            return implode('|', array_map(function ($v) {
                return implode('&', str_split($v));
            }, explode('|', $codes)));
        } elseif (strpos($codes, ' ') !== false) {
            return implode('|', array_map(function ($v) {
                return implode('&', explode(' ', $v));
            }, explode('|', $codes)));
        } else {
            return implode('&', str_split($codes));
        }
    }

    //生成老系统的投注单
    public function toOld($codes)
    {
        return $codes;
    }

    public function getCombination($aBaseArray, $iSelectNum)
    {
        $iBaseNum = count($aBaseArray);
        if ($iSelectNum > $iBaseNum) {
            return [];
        }
        if ($iSelectNum == 1) {
            return $aBaseArray;
        }
        if ($iBaseNum == $iSelectNum) {
            return array(implode(' ', $aBaseArray));
        }
        $sString = '';
        $sLastString = '';
        $sTempStr = '';
        $aResult = [];
        for ($i = 0; $i < $iSelectNum; $i++) {
            $sString .= '1';
            $sLastString .= '1';
        }
        for ($j = 0; $j < $iBaseNum - $iSelectNum; $j++) {
            $sString .= '0';
        }
        for ($k = 0; $k < $iSelectNum; $k++) {
            $sTempStr .= $aBaseArray[$k] . ' ';
        }
        $aResult[] = trim($sTempStr);
        $sTempStr = '';
        while (substr($sString, -$iSelectNum) != $sLastString) {
            $aString = explode('10', $sString, 2);
            $aString[0] = $this->strOrder($aString[0], true);
            $sString = $aString[0] . '01' . $aString[1];
            for ($k = 0; $k < $iBaseNum; $k++) {
                if ($sString{$k} == '1') {
                    $sTempStr .= $aBaseArray[$k] . ' ';
                }
            }
            $aResult[] = trim(substr($sTempStr, 0, -1));
            $sTempStr = '';
        }
        return $aResult;
    }

    public function getCombinCount($iBaseNumber, $iSelectNumber)
    {
        if ($iSelectNumber > $iBaseNumber) {
            return 0;
        }
        if ($iBaseNumber == $iSelectNumber || $iSelectNumber == 0) {
            return 1; //全选
        }
        if ($iSelectNumber == 1) {
            return $iBaseNumber; //选一个数
        }
        $iNumerator = 1; //分子
        $iDenominator = 1; //分母
        for ($i = 0; $i < $iSelectNumber; $i++) {
            $iNumerator *= $iBaseNumber - $i; //n*(n-1)...(n-m+1)
            $iDenominator *= $iSelectNumber - $i; //(n-m)....*2*1
        }
        return $iNumerator / $iDenominator;
    }

    // 字符排序
    public function strOrder($sString = '', $bDesc = false)
    {
        if ($sString == '') {
            return $sString;
        }
        $aString = str_split($sString);
        if ($bDesc) {
            rsort($aString);
        } else {
            sort($aString);
        }
        return implode('', $aString);
    }

    public function _ArrayFlip($aArr)
    {
        if (empty($aArr) || !is_array($aArr)) {
            return $aArr;
        }
        $aNewArr = [];
        foreach ($aArr as $k => $v) {
            $aNewArr[$v][] = $k;
        }
        return $aNewArr;
    }

    public function getRepeat($aCode, $iRepeats = 2)
    {
        $result = [];
        for ($ii = 0, $iiMax = count($aCode); $ii < $iiMax; $ii++) {
            $tCode = explode(' ', $aCode[$ii]);
            $result[$ii] = '';
            for ($iii = 0; $iii < $iRepeats; $iii++) {
                $result[$ii] .= $tCode[$iii] . ' ' . $tCode[$iii] . ' ';
            }
        }
        return $result;
    }

    /* 组合 数 */
    public function nCr($n, $r)
    {
        if ($r > $n) {
            return 0;
        }
        if (($n - $r) < $r) {
            return $this->nCr($n, ($n - $r));
        }
        $return = 1;
        for ($i = 0; $i < $r; $i++) {
            $return *= ($n - $i) / ($i + 1);
        }
        return $return;
    }

    /* 排列 数 */
    public function nPr($n, $r)
    {
        if ($r > $n) {
            return 0;
        }
        if ($r) {
            return $n * ($this->nPr($n - 1, $r - 1));
        } else {
            return 1;
        }
    }
}
