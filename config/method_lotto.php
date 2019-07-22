<?php

return [

    // 三星 - 前三 - 直选3
    'LTQ3ZX3' => [
        'name'      => '前三直选复式',
        'group'     => 'SM',
        'row'       => 'zhixuan',
        'method'    => 'LTQ3ZX3',
        'total'     => 990,
        'levels'    => [
            '1'     => [
                'position'  => ['1', '2', '3'],
                'count'     => 1,
                'prize'     => 1782.00
            ],
        ],
    ],

    // 三星 - 前三 - 直选3 - 单式
    'LTQ3ZX3_S'=> [
        'name'      => '前三直选单式',
        'group'     => 'SM',
        'row'       => 'zhixuan',
        'method'    => 'LTQ3ZX3_S',
        'total'     => 990,
        'levels' => [
            '1'=> [
                'position'  => ['1', '2', '3'],
                'count'     => 1,
                'prize'     => 1782.00
            ],
        ],
    ],

    // 三星 - 前三 - 组选 - 复式
    'LTQ3ZU3'=> [
        'name'      => '前三组选复式',
        'group'     => 'SM',
        'row'       => 'zuxuan',
        'method'    => 'LTQ3ZU3',
        'total'     => 165,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3'],
                'count'     => 1,
                'prize'     => 297.00
            ],
        ],
    ],

    // 三星 - 前三 - 组选 - 单式
    'LTQ3ZU3_S' => [
        'name'      => '前三组选单式',
        'group'     => 'SM',
        'row'       => 'zuxuan',
        'method'    => 'LTQ3ZU3_S',
        'total'     => 165,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3'],
                'count'     => 1,
                'prize'     => 297.00
            ],
        ],
    ],

    // 三星 - 前三 - 组选 - 胆拖
    'LTQ3ZU3DT' => [
        'name'      => '前三组选胆拖',
        'group'     => 'SM',
        'row'       => 'zuxuan',
        'method'    => 'LTQ3ZU3DT',
        'total'     => 165,
        'levels' => [
            '1'=> [
                'position'  => ['1','2','3'],
                'count'     => 1,
                'prize'     => 297.00
            ],
        ],
    ],

    // 前二星 - 直选- 复试
    'LTQ2ZX2' => [
        'name'      => '前二直选复式',
        'group'     => 'EM',
        'row'       => 'zhixuan',
        'method'    => 'LTQ2ZX2',
        'total'     => 110,
        'levels' => [
            '1'=> [
                'position'  => ['1','2'],
                'count'     => 1,
                'prize'     => 198.00
            ],
        ],
    ],

    // 前二星 - 直选- 单式
    'LTQ2ZX2_S' => [
        'name'      => '前二直选单式',
        'group'     => 'EM',
        'row'       => 'zhixuan',
        'method'    => 'LTQ2ZX2_S',
        'total'     => 110,
        'levels' => [
            '1'=> [
                'position'  => ['1','2'],
                'count'     => 1,
                'prize'     => 198.00
            ],
        ],
    ],

    // 前二星 - 组选- 复式
    'LTQ2ZU2'=> [
        'name'      => '前二组选复式',
        'group'     => 'EM',
        'row'       => 'zuxuan',
        'method'    => 'LTQ2ZU2',
        'total'     => 55,
        'levels' => [
            '1'=> [
                'position'  => ['1','2'],
                'count'     => 1,
                'prize'     => 99.00
            ],
        ],
    ],

    // 前二星 - 组选- 单式
    'LTQ2ZU2_S'=> [
        'name'      => '前二组选单式',
        'group'     => 'EM',
        'row'       => 'zuxuan',
        'method'    => 'LTQ2ZU2_S',
        'total'     => 55,
        'levels' => [
            '1' => [
                'position'  => ['1','2'],
                'count'     => 1,
                'prize'     => 99.00
            ],
        ],
    ],

    // 前二星 - 组选- 胆拖
    'LTQ2DTZU2'=> [
        'name'      => '前二组选胆拖',
        'group'     => 'EM',
        'row'       => 'zuxuan',
        'method'    => 'LTQ2DTZU2',
        'total'     => 55,
        'levels' => [
            '1'=> [
                'position'  => ['1','2'],
                'count'     => 1,
                'prize'     => 99.00
            ],
        ],
    ],

    //不定位 - 前三
    'LTBDW' => [
        'name'      => '不定位',
        'group'     => 'BDW',
        'row'       => 'budingwei',
        'method'    => 'LTBDW',
        'total'     => 11,
        'levels' => [
            '1'=> [
                'position'  => ['1','2','3'],
                'count'     => 3,
                'prize'     => 6.60
            ],
        ],
    ],

    // 定位胆 - 1
    'LTDWD' => [
        'name'      => '定位胆',
        'group'     => 'DWD',
        'row'       => 'dingweidan',
        'method'    => 'LTDWD',
        'total'     => 11,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3'],
                'count'     => 1,
                'prize'     => 19.80
            ],
        ],
    ],

    // 定位胆 - 1
    'LTDWD_1' => [
        'name'      => '定位胆第一位',
        'group'     => 'DWD',
        'row'       => 'dingweidan',
        'method'    => 'LTDWD_1',
        'hidden'    => true,
        'total'     => 11,
        'levels' => [
            '1' => [
                'position'  => ['1'],
                'count'     => 1,
                'prize'     => 19.80
            ],
        ],
    ],

    // 定位胆 - 2
    'LTDWD_2' => [
        'name'      => '定位胆第二位',
        'group'     => 'DWD',
        'row'       => 'dingweidan',
        'method'    => 'LTDWD_2',
        'hidden'    => true,
        'total'     => 11,
        'levels' => [
            '1' => [
                'position'  => ['2'],
                'count'     => 1,
                'prize'     => 19.80
            ],
        ],
    ],

    // 定位胆 - 3
    'LTDWD_3' => [
        'name'      => '定位胆第三位',
        'group'     => 'DWD',
        'row'       => 'dingweidan',
        'method'    => 'LTDWD_3',
        'total'     => 11,
        'hidden'    => true,
        'levels' => [
            '1' => [
                'position'  => ['3'],
                'count'     => 1,
                'prize'     => 19.80
            ],
        ],
    ],

    // 趣味 - 定单双
    'LTDDS' => [
        'name'      => '定单双',
        'group'     => 'QW',
        'row'       => 'quwei',
        'method'    => 'LTDDS',
        'total'     => 462,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '0单5双',
                'count'     => 1,
                'prize'     => 831.00
            ],
            '2' => [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '5单0双',
                'count'     => 6,
                'prize'     => 138.00
            ],
            '3' => [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '1单4双',
                'count'     => 30,
                'prize'     => 27.70
            ],
            '4' => [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '4单1双',
                'count'     => 75,
                'prize'     => 11.00
            ],
            '5' => [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '2单3双',
                'count'     => 150,
                'prize'     => 5.50
            ],
            '6'=> [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '3单2双',
                'count'     => 200,
                'prize'     => 4.10
            ],
        ],
    ],

    // 猜中位
    'LTCZW' => [
        'name'      => '猜中位',
        'group'     => 'QW',
        'row'       => 'quwei',
        'method'    => 'LTCZW',
        'total'     => 462,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '中位:3或9',
                'count'     => 28,
                'prize'     => 29.70
            ],
            '2' =>  [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '中位:4或8',
                'count'     => 63,
                'prize'     => 13.20
            ],
            '3' =>  [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '中位:5或7',
                'count'     => 90,
                'prize'     => 9.20
            ],
            '4' =>  [
                'position'  => ['1','2','3','4','5'],
                'levelName' => '中位:6',
                'count'     => 100,
                'prize'     => 8.30
            ],
        ],
    ],

    // 任选
    'LTRX1' => [
        'name'      => '任选一中一',
        'group'     => 'RXFS',
        'row'       => 'renxuanfushi',
        'method'    => 'LTRX1',
        'total'     => 11,
        'levels'    => [
            '1' => [
                'position'   => ['1','2','3','4','5'],
                'count'     => 5,
                'prize'     => 3.90
            ],
        ],
    ],

    // 任选二中二
    'LTRX2'=> [
        'name'      => '任选二中二',
        'group'     => 'RXFS',
        'row'       => 'renxuanfushi',
        'method'    => 'LTRX2',
        'total'     => 55,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 10,
                'prize'     => 9.90
            ],
        ],
    ],

    // 任选三中三
    'LTRX3'=> [
        'name'      => '任选三中三',
        'group'     => 'RXFS',
        'row'       => 'renxuanfushi',
        'method'    => 'LTRX3',
        'total'     => 165,
        'levels' => [
            '1'=> [
                'position'  => ['1','2','3','4','5'],
                'count'     => 10,
                'prize'     => 29.70
            ],
        ],
    ],

    // 任选四中四
    'LTRX4' => [
        'name'      => '任选四中四',
        'group'     => 'RXFS',
        'row'       => 'renxuanfushi',
        'method'    => 'LTRX4',
        'total'     => 330,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 5,
                'prize'     => 118.00
            ],
        ],
    ],

    // 任选五中五
    'LTRX5'=> [
        'name'      => '任选五中五',
        'group'     => 'RXFS',
        'row'       => 'renxuanfushi',
        'method'    => 'LTRX5',
        'total'     => 462,
        'levels' => [
            '1'=> [
                'position'  => ['1','2','3','4','5'],
                'count'     => 1,
                'prize'     => 831.00
            ],
        ],
    ],

    // 任选六中五
    'LTRX6'=> [
        'name' => '任选六中五',
        'group'     => 'RXFS',
        'row'       => 'renxuanfushi',
        'method'    => 'LTRX6',
        'total'     => 462,
        'levels' => [
            '1'=> [
                'position'  => ['1','2','3','4','5'],
                'count'     => 6,
                'prize'     => 138.00
            ],
        ],
    ],

    // 任选七中五
    'LTRX7'=> [
        'name'      => '任选七中五',
        'group'     => 'RXFS',
        'row'       => 'renxuanfushi',
        'method'    => 'LTRX7',
        'total'     => 330,
        'levels' => [
            '1'=> [
                'position'  => ['1','2','3','4','5'],
                'count'     => 15,
                'prize'     => 39.60
            ],
        ],
    ],

    // 任选八中五
    'LTRX8'=> [
        'name'      => '任选八中五',
        'group'     => 'RXFS',
        'row'       => 'renxuanfushi',
        'method'    => 'LTRX8',
        'total'     => 165,
        'levels'    => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 20,
                'prize'     => 14.80
            ],
        ],
    ],

    // 任选单式 - 任选一中一
    'LTRX1_S' => [
        'name'      => '任选一中一',
        'group'     => 'RXDS',
        'row'       => 'renxuandanshi',
        'method'    => 'LTRX1_S',
        'total'     => 11,
        'levels' => [
            '1'=> [
                'position'  => ['1','2','3','4','5'],
                'count'     => 5,
                'prize'     => 3.90
            ],
        ],
    ],

    // 任选单式 - 任选二中二
    'LTRX2_S'=> [
        'name'      => '任选二中二',
        'group'     => 'RXDS',
        'row'       => 'renxuandanshi',
        'method'    => 'LTRX2_S',
        'total'     => 55,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 10,
                'prize'     => 9.90
            ],
        ],
    ],

    // 任选单式 - 任选三中三
    'LTRX3_S'=> [
        'name'      => '任选三中三',
        'group'     => 'RXDS',
        'row'       => 'renxuandanshi',
        'method'    => 'LTRX3_S',
        'total'     => 165,
        'levels' => [
            '1'=> [
                'position'  => ['1','2','3','4','5'],
                'count'     => 10,
                'prize'     => 29.70
            ],
        ],
    ],

    // 任选单式 - 任选四中四
    'LTRX4_S' => [
        'name'      => '任选四中四',
        'group'     => 'RXDS',
        'row'       => 'renxuandanshi',
        'method'    => 'LTRX4_S',
        'total'     => 330,
        'levels'    => [
            '1'     => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 5,
                'prize'     => 118.00
            ],
        ],
    ],

    // 任选单式 - 任选五中五
    'LTRX5_S'=> [
        'name'      => '任选五中五',
        'group'     => 'RXDS',
        'row'       => 'renxuandanshi',
        'method'    => 'LTRX5_S',
        'total'     => 462,
        'levels' => [
            '1'  => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 1,
                'prize'     => 831.00
            ],
        ],
    ],

    // 任选单式 - 任选六中五
    'LTRX6_S' => [
        'name'      => '任选六中五',
        'group'     => 'RXDS',
        'row'       => 'renxuandanshi',
        'method'    => 'LTRX6_S',
        'total'     => 462,
        'levels' => [
            '1'  => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 6,
                'prize'     => 138.00
            ],
        ],
    ],

    // 任选单式 - 任选七中五
    'LTRX7_S' => [
        'name'      => '任选七中五',
        'group'     => 'RXDS',
        'row'       => 'renxuandanshi',
        'method'    => 'LTRX7_S',
        'total'     => 330,
        'levels'    => [
            '1'     => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 15,
                'prize'     => 39.60
            ],
        ],
    ],

    // 任选单式 - 任选八中五
    'LTRX8_S' => [
        'name'      => '任选八中五',
        'group'     => 'RXDS',
        'row'       => 'renxuandanshi',
        'method'    => 'LTRX8_S',
        'total'     => 165,
        'levels' => [
            '1'  => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 20,
                'prize'     => 14.80
            ],
        ],
    ],

     // 任选胆托 - 二中二
    'LTRXDT2'=> [
        'name'      => '任选胆托二中二',
        'group'     => 'RXDT',
        'row'       => 'renxuandantuo',
        'method'    => 'LTRXDT2',
        'total'     => 55,
        'levels' => [
            '1'  => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 10,
                'prize'     => 9.90
            ],
        ],
    ],

    // 任选胆托 - 三中三
    'LTRXDT3'=> [
        'name'      => '任选胆托三中三',
        'group'     => 'RXDT',
        'row'       => 'renxuandantuo',
        'method'    => 'LTRXDT3',
        'total'     => 165,
        'levels' => [
            '1'  => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 10,
                'prize'     => 29.70
            ],
        ],
    ],

    // 任选胆托 - 四中四
    'LTRXDT4' => [
        'name'      => '任选胆托四中四',
        'group'     => 'RXDT',
        'row'       => 'renxuandantuo',
        'method'    => 'LTRXDT4',
        'total'     => 330,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 5,
                'prize'     => 118.00
            ],
        ],
    ],

    // 任选胆托 - 五中五
    'LTRXDT5' => [
        'name'      => '任选胆托五中五',
        'group'     => 'RXDT',
        'row'       => 'renxuandantuo',
        'method'    => 'LTRXDT5',
        'total'     => 462,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 1,
                'prize'     => 831.00
            ],
        ],
    ],

    // 任选胆托 - 六中五
    'LTRXDT6' => [
        'name'      => '任选胆托六中五',
        'group'     => 'RXDT',
        'row'       => 'renxuandantuo',
        'method'    => 'LTRXDT6',
        'total'     => 462,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 6,
                'prize'     => 138.00
            ],
        ],
    ],

    // 任选胆托 - 七中五
    'LTRXDT7' => [
        'name'      => '任选胆托七中五',
        'group'     => 'RXDT',
        'row'       => 'renxuandantuo',
        'method'    => 'LTRXDT7',
        'total'     => 330,
        'levels' => [
            '1' => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 15,
                'prize'     => 39.60
            ],
        ],
    ],

    // 任选胆托 - 八中五
    'LTRXDT8' => [
        'name'      => '任选胆托八中五',
        'group'     => 'RXDT',
        'row'       => 'renxuandantuo',
        'method'    => 'LTRXDT8',
        'total'     => 165,
        'levels'    => [
            '1'     => [
                'position'  => ['1','2','3','4','5'],
                'count'     => 20,
                'prize'     => 14.80
            ],
        ],
    ],
];
