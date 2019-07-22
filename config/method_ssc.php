<?php
// 时时彩类型 - 数字型
return [
    // 五星
    'ZX5'=> [
        'name'      => '直选复式',
        'group'     => 'WX',
        'row'       => 'zhixuan',
        'method'    => 'ZX5',
        'total'     => 100000,
        'levels'    => [
            '1'=> [
                'position' => ['w','q','b','s','g'],
                'count'     => 1,
                'prize'     => 180000.00
            ]

        ],
    ],

    // 五星 - 直选复试
    'ZX5_S' => [
        'name'      => '直选单式',
        'group'     => 'WX',
        'row'       => 'zhixuan',
        'method'    => 'ZX5_S',
        'total'     => 100000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b','s','g'],
                'count'     => 1,
                'prize'     => 180000.00
            ]
        ],
    ],

    'ZH5'   => [
        'name'      => '直选组合',
        'group'     => 'WX',
        'row'       => 'zhixuan',
        'method'    => 'ZH5',
        'jzjd'      => 1,
        'total'     => 100000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b','s','g'],
                'levelName' => '一等奖',
                'count'     => 1,
                'prize'     => 180000.00
            ],

            '2'=> [
                'position'  => ['q', 'b', 's', 'g'],
                'levelName' => '二等奖',
                'count'     => 10,
                'prize'     => 18000.00
            ],

            '3'=> [
                'position'  => ['b', 's', 'g'],
                'levelName' => '三等奖',
                'count'     => 100,
                'prize'     => 1800.00
            ],

            '4'=> [
                'position'  => ['s', 'g'],
                'levelName' => '四等奖',
                'count'     => 1000,
                'prize'     => 180.00
            ],

            '5'=> [
                'position'  => ['g'],
                'levelName' => '五等奖',
                'count'     => 10000,
                'prize'     => 18.00
            ]
        ],
    ],

    // 组选 - 120
    'WXZU120'=> [
        'name'      => '组选120',
        'group'     => 'WX',
        'row'       => 'zuxuan',
        'method'    => 'WXZU120',
        'total'     => 100000,
        'levels'    => [
            '1'=> [
                'position'  => ['w','q','b','s','g'],
                'count'     => 120,
                'prize'     => 1500.00
            ],
        ],
    ],

    // 组选 - 60
    'WXZU60'=> [
        'name'      => '组选60',
        'group'     => 'WX',
        'row'       => 'zuxuan',
        'method'    => 'WXZU60',
        'total'     => 100000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b','s','g'],
                'count'     => 60,
                'prize'     => 3000.00
            ],
        ],
    ],

    // 组选 - 30
    'WXZU30'=> [
        'name'      => '组选30',
        'group'     => 'WX',
        'row'       => 'zuxuan',
        'method'    => 'WXZU30',
        'total'     => 100000,
        'levels' => [
            '1' =>  [
                'position'  => ['w','q','b','s','g'],
                'count'     => 30,
                'prize'     => 6000.00
            ],
        ],
    ],

    // 组选 - 20
    'WXZU20'=> [
        'name'      => '组选20',
        'group'     => 'WX',
        'row'       => 'zuxuan',
        'method'    => 'WXZU20',
        'total'     => 100000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b','s','g'],
                'count'     => 20,
                'prize'     => 9000.00
            ],
        ],
    ],

    // 组选 - 10
    'WXZU10'=> [
        'name'      => '组选10',
        'group'     => 'WX',
        'row'       => 'zuxuan',
        'method'    => 'WXZU10',
        'total'     => 100000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b','s','g'],
                'count'     => 10,
                'prize'     => 18000.00
            ],
        ],
    ],

    // 组选 - 5
    'WXZU5'=> [
        'name'      => '组选5',
        'group'     => 'WX',
        'row'       => 'zuxuan',
        'method'    => 'WXZU5',
        'total'     => 100000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b','s','g'],
                'count'     => 5,
                'prize'     => 36000.00
            ],
        ],
    ],

    // 四星 - 直选
    'ZX4'=> [
        'name'      => '直选复式',
        'group'     => 'SX',
        'row'       => 'zhixuan',
        'method'    => 'ZX4',
        'total'     => 10000,
        'levels' => [
            '1'=> [
                'position'  => ['q','b','s','g'],
                'count'     => 1,
                'prize'     => 18000.00
            ],
        ],
    ],

    // 四星 - 直选单式
    'ZX4_S'=> [
        'name'      => '直选单式',
        'group'     => 'SX',
        'row'       => 'zhixuan',
        'method'    => 'ZX4_S',
        'total'     => 10000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s','g'],
                'count'     => 1,
                'prize'     => 18000.00
            ],
        ],
    ],

    // 组合4
    'ZH4'=> [
        'name'      => '直选组合',
        'group'     => 'SX',
        'row'       => 'zhixuan',
        'method'    => 'ZH4',
        'jzjd'      => 1,
        'total'     => 10000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s','g'],
                'levelName' => '一等奖',
                'count'     => 1,
                'prize'     => 18000.00
            ],

            '2' => [
                'position'  => ['b','s','g'],
                'levelName' => '二等奖',
                'count'     => 10,
                'prize'     => 1800.00
            ],

            '3' => [
                'position'  => ['s','g'],
                'levelName' => '三等奖',
                'count'     => 100,
                'prize'     => 180.00
            ],

            '4' => [
                'position'  => ['g'],
                'levelName' => '四等奖',
                'count'     => 1000,
                'prize'     => 18.00
            ],
        ],
    ],

    // 四星 - 组选24
    'SXZU24'=> [
        'name'      => '组选24',
        'group'     => 'SX',
        'row'       => 'zuxuan',
        'method'    => 'SXZU24',
        'total'     => 10000,
        'levels'    => [
            '1' => [
                'position'  => ['q','b','s','g'],
                'count'     => 24,
                'prize'     => 750.00
            ],
        ],
    ],

    // 四星 - 组选12
    'SXZU12'=> [
        'name'      => '组选12',
        'group'     => 'SX',
        'row'       => 'zuxuan',
        'method'    => 'SXZU12',
        'total'     => 10000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s','g'],
                'count'     => 12,
                'prize'     => 1500.00
            ],
        ],
    ],

    // 四星 - 组选6
    'SXZU6'=> [
        'name'      => '组选6',
        'group'     => 'SX',
        'row'       => 'zuxuan',
        'method'    => 'SXZU6',
        'total'     => 10000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s','g'],
                'count'     => 6,
                'prize'     => 3000.00
            ],
        ],
    ],

    // 四星 - 组选4
    'SXZU4' => [
        'name'      => '组选4',
        'group'     => 'SX',
        'row'       => 'zuxuan',
        'method'    => 'SXZU4',
        'total'     => 10000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s','g'],
                'count'     => 4,
                'prize'     => 4500.00
            ],
        ],
    ],


    //三星 - 前三
    'QZX3' => [
        'name'      => '直选复式',
        'group'     => 'Q3',
        'row'       => 'zhixuan',
        'method'    => 'QZX3',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三星 - 前三单式
    'QZX3_S' => [
        'name'      => '直选单式',
        'group'     => 'Q3',
        'row'       => 'zhixuan',
        'method'    => 'QZX3_S',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三星 - 前三组合
    'QZH3' => [
        'name'      => '直选组合',
        'group'     => 'Q3',
        'row'       => 'zhixuan',
        'method'    => 'QZH3',
        'jzjd'      => 1,
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'count'     => 1,
                'prize'     => 1800.00
            ],

            '2' => [
                'position'  => ['q','b'],
                'count'     => 10,
                'prize'     => 180.00
            ],

            '3' => [
                'position'  => ['b'],
                'count'     => 100,
                'prize'     => 18.00
            ],
        ],
    ],

    // 三星 - 前三直选和值
    'QZXHZ' => [
        'name'      => '直选和值',
        'group'     => 'Q3',
        'row'       => 'zhixuan',
        'method'    => 'QZXHZ',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三星 - 前三直选跨度
    'QZXKD' => [
        'name'      => '直选跨度',
        'group'     => 'Q3',
        'row'       => 'zhixuan',
        'method'    => 'QZXKD',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三线 组三
    'QZU3'=> [
        'name'      => '组三',
        'group'     => 'Q3',
        'row'       => 'zuxuan',
        'method'    => 'QZU3',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'count'     => 3,
                'prize'     => 600.00
            ],
        ],
    ],

    // 三星 - 组三单式
    'QZU3_S'=> [
        'name'      => '组三单式',
        'group'     => 'Q3',
        'row'       => 'zuxuan',
        'method'    => 'QZU3_S',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'count'     => 3,
                'prize'     => 600.00
            ],
        ],
    ],

    // 三星 - 前三组六
    'QZU6'=> [
        'name'      => '组六',
        'group'     => 'Q3',
        'row'       => 'zuxuan',
        'method'    => 'QZU6',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b'],
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 三星 - 前三组六单式
    'QZU6_S'=> [
        'name'      => '组六单式',
        'group'     => 'Q3',
        'row'       => 'zuxuan',
        'method'    => 'QZU6_S',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 前三 - 前三混合组选
    'QHHZX' => [
        'name'      => '混合组选',
        'group'     => 'Q3',
        'row'       => 'zuxuan',
        'method'    => 'QHHZX',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'levelName' => '组三',
                'count'     => 3,
                'prize'     => 600.00
            ],
            '2' => [
                'position'  => ['w','q','b'],
                'levelName' => '组六',
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 前三 - 组选和值
    'QZUHZ' => [
        'name'      => '组选和值',
        'group'     => 'Q3',
        'row'       => 'zuxuan',
        'method'    => 'QZUHZ',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'levelName' =>'组三',
                'count'     => 3,
                'prize'     => 600.00
            ],
            '2' => [
                'position'  => ['w','q','b'],
                'levelName' => '组六',
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 前三组选包胆
    'QZU3BD'=> [
        'name'      => '组选包胆',
        'group'     => 'Q3',
        'row'       => 'zuxuan',
        'method'    => 'QZU3BD',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['w','q','b'],
                'levelName' => '组三',
                'count'     => 3,
                'prize'     => 600.00
            ],
            '2' => [
                'position'  => ['w','q','b'],
                'levelName' => '组六',
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 三星 - 趣味 - 和值尾数
    'QHZWS' =>   [
        'name'      => '和值尾数',
        'group'     => 'Q3',
        'row'       => 'qita',
        'method'    => 'QHZWS',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'count'     => 1,
                'prize'     => 18.00
            ],
        ],
    ],

    // 三星 - 趣味 - 特殊号
    'QTS3'=> [
        'name'      => '特殊号',
        'group'     => 'Q3',
        'row'       => 'qita',
        'method'    => 'QTS3',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'levelName' =>'豹子',
                'position'  => ['w','q','b'],
                'count'     => 10,
                'prize'     => 180.00
            ],

            '2' => [
                'position'  =>  ['w','q','b'],
                'levelName' =>  '顺子',
                'count'     =>  60,
                'prize'     =>  30.00
            ],

            '3' => [
                'position'  => ['w','q','b'],
                'levelName' => '对子',
                'count'     => 270,
                'prize'     => 6.60
            ],
        ],
    ],

    // =========================== 中三 ========================

    //三星 - 中三
    'ZZX3' => [
        'name'      => '直选复式',
        'group'     => 'Z3',
        'row'       => 'zhixuan',
        'method'    => 'ZZX3',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三星 - 中三 - 单式
    'ZZX3_S' => [
        'name'      => '直选单式',
        'group'     => 'Z3',
        'row'       => 'zhixuan',
        'method'    => 'ZZX3_S',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['q','b','s'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三星 - 中三组合
    'ZZH3' => [
        'name'      => '直选组合',
        'group'     => 'Z3',
        'row'       => 'zhixuan',
        'method'    => 'ZZH3',
        'jzjd'      => 1,
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s'],
                'count'     => 1,
                'prize'     => 1800.00
            ],

            '2' => [
                'position'  => ['b','s'],
                'count'     => 10,
                'prize'     => 180.00
            ],

            '3' => [
                'position'  => ['s'],
                'count'     => 100,
                'prize'     => 18.00
            ],
        ],
    ],

    // 三星 - 中三直选和值
    'ZZXHZ' => [
        'name'      => '直选和值',
        'group'     => 'Z3',
        'row'       => 'zhixuan',
        'method'    => 'ZZXHZ',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三星 - 前三直选跨度
    'ZZXKD' => [
        'name'      => '直选跨度',
        'group'     => 'Z3',
        'row'       => 'zhixuan',
        'method'    => 'ZZXKD',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['q','b','s'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三线 组三
    'ZZU3'=> [
        'name'      => '组三',
        'group'     => 'Z3',
        'row'       => 'zuxuan',
        'method'    => 'ZZU3',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s'],
                'count'     => 3,
                'prize'     => 600.00
            ],
        ],
    ],

    // 三星 - 组三单式
    'ZZU3_S'=> [
        'name'      => '组三单式',
        'group'     => 'Z3',
        'row'       => 'zuxuan',
        'method'    => 'ZZU3_S',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s'],
                'count'     => 3,
                'prize'     => 600.00
            ],
        ],
    ],

    // 三星 - 前三组六
    'ZZU6'=> [
        'name'      => '组六',
        'group'     => 'Z3',
        'row'       => 'zuxuan',
        'method'    => 'ZZU6',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['q','b','s'],
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 三星 - 前三组六单式
    'ZZU6_S'=> [
        'name'      => '组六单式',
        'group'     => 'Z3',
        'row'       => 'zuxuan',
        'method'    => 'ZZU6_S',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s'],
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 前三 - 前三混合组选
    'ZHHZX' => [
        'name'      => '混合组选',
        'group'     => 'Z3',
        'row'       => 'zuxuan',
        'method'    => 'ZHHZX',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s'],
                'levelName' => '组三',
                'count'     => 3,
                'prize'     => 600.00
            ],
            '2' => [
                'position'  => ['q','b','s'],
                'levelName' => '组六',
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 前三 - 组选和值
    'ZZUHZ' => [
        'name'      => '组选和值',
        'group'     => 'Z3',
        'row'       => 'zuxuan',
        'method'    => 'ZZUHZ',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s'],
                'levelName' =>'组三',
                'count'     => 3,
                'prize'     => 600.00
            ],
            '2' => [
                'position'  => ['q','b','s'],
                'levelName' => '组六',
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 前三组选包胆
    'ZZU3BD'=> [
        'name'      => '组选包胆',
        'group'     => 'Z3',
        'row'       => 'zuxuan',
        'method'    => 'ZZU3BD',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['q','b','s'],
                'levelName' => '组三',
                'count'     => 3,
                'prize'     => 600.00
            ],
            '2' => [
                'position'  => ['q','b','s'],
                'levelName' => '组六',
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 三星 - 趣味 - 和值尾数
    'ZHZWS' =>   [
        'name'      => '和值尾数',
        'group'     => 'Z3',
        'row'       => 'quwei',
        'method'    => 'ZHZWS',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['q','b','s'],
                'count'     => 1,
                'prize'     => 18.00
            ],
        ],
    ],

    // 三星 - 趣味 - 特殊号
    'ZTS3'=> [
        'name'      => '特殊号',
        'group'     => 'Z3',
        'row'       => 'quwei',
        'method'    => 'ZTS3',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'levelName' =>'豹子',
                'position'  => ['q','b','s'],
                'count'     =>10,
                'prize'     =>180.00
            ],

            '2' => [
                'position'  => ['q','b','s'],
                'levelName' =>  '顺子',
                'count'     =>  60,
                'prize'     =>  30.00
            ],

            '3' => [
                'position'  => ['q','b','s'],
                'levelName' => '对子',
                'count'     => 270,
                'prize'     => 6.60
            ],
        ],
    ],

    // ============================= 后三 ================================

    // 三星 - 后三
    'HZX3' => [
        'name'      => '直选复式',
        'group'     => 'H3',
        'row'       => 'zhixuan',
        'method'    => 'HZX3',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三星 - 后三 - 单式
    'HZX3_S' => [
        'name'      => '直选单式',
        'group'     => 'H3',
        'row'       => 'zhixuan',
        'method'    => 'HZX3_S',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['b','s', 'g'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三星 - 后三 - 组合
    'HZH3' => [
        'name'      => '直选组合',
        'group'     => 'H3',
        'row'       => 'zhixuan',
        'method'    => 'HZH3',
        'jzjd'      => 1,
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'count'     => 1,
                'prize'     => 1800.00
            ],

            '2' => [
                'position'  => ['s', 'g'],
                'count'     => 10,
                'prize'     => 180.00
            ],

            '3' => [
                'position'  => ['g'],
                'count'     => 100,
                'prize'     => 18.00
            ],
        ],
    ],

    // 三星 - 后三 - 直选和值
    'HZXHZ' => [
        'name'      => '直选和值',
        'group'     => 'H3',
        'row'       => 'zhixuan',
        'method'    => 'HZXHZ',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三星 - 后三 - 直选跨度
    'HZXKD' => [
        'name'      => '直选跨度',
        'group'     => 'H3',
        'row'       => 'zhixuan',
        'method'    => 'HZXKD',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['b','s', 'g'],
                'count'     => 1,
                'prize'     => 1800.00
            ],
        ],
    ],

    // 三线 后三 - 组三
    'HZU3'=> [
        'name'      => '组三',
        'group'     => 'H3',
        'row'       => 'zuxuan',
        'method'    => 'HZU3',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'count'     => 3,
                'prize'     => 600.00
            ],
        ],
    ],

    // 三星 - 后三 - 组三单式
    'HZU3_S'=> [
        'name'      => '组三单式',
        'group'     => 'H3',
        'row'       => 'zuxuan',
        'method'    => 'HZU3_S',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'count'     => 3,
                'prize'     => 600.00
            ],
        ],
    ],

    // 三星 - 后三 - 前三组六
    'HZU6'=> [
        'name'      => '组六',
        'group'     => 'H3',
        'row'       => 'zuxuan',
        'method'    => 'HZU6',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['b','s', 'g'],
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 三星 - 后三 - 前三组六单式
    'HZU6_S'=> [
        'name'      => '组六单式',
        'group'     => 'H3',
        'row'       => 'zuxuan',
        'method'    => 'HZU6_S',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 前三 - 后三 - 前三混合组选
    'HHHZX' => [
        'name'      => '混合组选',
        'group'     => 'H3',
        'row'       => 'zuxuan',
        'method'    => 'HHHZX',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'levelName' => '组三',
                'count'     => 3,
                'prize'     => 600.00
            ],
            '2' => [
                'position'  => ['b','s', 'g'],
                'levelName' => '组六',
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 前三 - 后三 - 组选和值
    'HZUHZ' => [
        'name'      => '组选和值',
        'group'     => 'H3',
        'row'       => 'zuxuan',
        'method'    => 'HZUHZ',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'levelName' =>'组三',
                'count'     => 3,
                'prize'     => 600.00
            ],
            '2' => [
                'position'  => ['b','s', 'g'],
                'levelName' => '组六',
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 三星  - 后三 - 组选包胆
    'HZU3BD'=> [
        'name'      => '组选包胆',
        'group'     => 'H3',
        'row'       => 'zuxuan',
        'method'    => 'HZU3BD',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'levelName' => '组三',
                'count'     => 3,
                'prize'     => 600.00
            ],
            '2' => [
                'position'  => ['b','s', 'g'],
                'levelName' => '组六',
                'count'     => 6,
                'prize'     => 300.00
            ],
        ],
    ],

    // 三星 - 后三 - 趣味 - 和值尾数
    'HHZWS' =>   [
        'name'      => '和值尾数',
        'group'     => 'H3',
        'row'       => 'quwei',
        'method'    => 'HHZWS',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['b','s', 'g'],
                'count'     => 1,
                'prize'     => 18.00
            ],
        ],
    ],

    // 三星 - 后三 - 趣味 - 特殊号
    'HTS3'=> [
        'name'      => '特殊号',
        'group'     => 'H3',
        'row'       => 'quwei',
        'method'    => 'HTS3',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'levelName' =>'豹子',
                'position'  => ['b','s', 'g'],
                'count'     =>10,
                'prize'     =>180.00
            ],

            '2' => [
                'position'  => ['b','s', 'g'],
                'levelName' =>  '顺子',
                'count'     =>  60,
                'prize'     =>  30.00
            ],

            '3' => [
                'position'  => ['b','s', 'g'],
                'levelName' => '对子',
                'count'     => 270,
                'prize'     => 6.60
            ],
        ],
    ],


    /**=================== 后二 ==================== */

    // 后二 - 直选复式
    'HZX2' => [
        'name'      => '后二复式',
        'group'     => 'EX',
        'row'       => 'zhixuan',
        'method'    => 'HZX2',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['s','g'],
                'count'     => 10,
                'prize'     => 180.00
            ],
        ],
    ],

    // 后二 - 直选复式 - 单式
    'HZX2_S'=> [
        'name'      => '后二单式',
        'group'     => 'EX',
        'row'       => 'zhixuan',
        'method'    => 'HZX2_S',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['s','g'],
                'count'     => 10,
                'prize'     => 180.00
            ],
        ],
    ],

    // 后二 - 直选和值
    'HZX2HZ'=> [
        'name'      => '后二和值',
        'group'     => 'EX',
        'row'       => 'zhixuan',
        'method'    => 'HZX2HZ',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['s','g'],
                'count'     => 10,
                'prize'     => 180.00
            ],
        ],
    ],

    // 后二 - 直选跨度
    'HZX2KD'=> [
        'name'      => '后二跨度',
        'group'     => 'EX',
        'row'       => 'zhixuan',
        'method'    => 'HZX2KD',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['s','g'],
                'count'     => 10,
                'prize'     => 180.00
            ],
        ],
    ],

    // 后二 - 组选
    'HZU2'=> [
        'name'      => '后二复式',
        'group'     => 'EX',
        'row'       => 'zuxuan',
        'method'    => 'ZU2',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['s','g'],
                'count'     => 20,
                'prize'     => 90.00
            ],
        ],
    ],

    // 后二 - 组选单式
    'HZU2_S'=> [
        'name'      => '后二单式',
        'group'     => 'EX',
        'row'       => 'zuxuan',
        'method'    => 'ZU2_S',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['s','g'],
                'count'     => 20,
                'prize'     => 90.00
            ],
        ],
    ],

    // 后二 - 组选和值
    'HZU2HZ'=> [
        'name'      => '后二和值',
        'group'     => 'EX',
        'row'       => 'zuxuan',
        'method'    => 'HZU2HZ',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['s','g'],
                'count'     => 20,
                'prize'     => 90.00
            ],
        ],
    ],

    // 后二 - 组选包胆
    'HZU2BD'=> [
        'name'      => '后二包胆',
        'group'     => 'EX',
        'row'       => 'zuxuan',
        'method'     => 'HZU2BD',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['s','g'],
                'count'     => 20,
                'prize'     => 90.00
            ],
        ],
    ],

    // 前二 - 直选复式
    'QZX2'=> [
        'name'      => '前二复式',
        'group'     => 'EX',
        'row'       => 'zhixuan',
        'method'    => 'QZX2',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w', 'q'],
                'count'     => 10,
                'prize'     => 180.00
            ],
        ],
    ],

    // 前二 - 直选单式
    'QZX2_S'=> [
        'name'      => '前二单式',
        'group'     => 'EX',
        'row'       => 'zhixuan',
        'method'    => 'QZX2_S',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w', 'q'],
                'count'     => 10,
                'prize'     => 180.00
            ],
        ],
    ],

    // 前二 - 直选和值
    'QZX2HZ'=> [
        'name'      => '前二和值',
        'group'     => 'EX',
        'row'       => 'zhixuan',
        'method'    => 'QZX2HZ',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position' => ['w','q'],
                'count'     => 10,
                'prize'     => 180.00
            ],
        ],
    ],

    // 前二 - 直选跨度
    'QZX2KD' => [
        'name'      => '前二跨度',
        'group'     => 'EX',
        'row'       => 'zhixuan',
        'method'    => 'QZX2KD',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['w', 'q'],
                'count'     => 10,
                'prize'     => 180.00
            ],
        ],
    ],

    // 前二 - 组选
    'QZU2'=> [
        'name'      => '前二复式',
        'group'     => 'EX',
        'row'       => 'zuxuan',
        'method'    => 'QZU2',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q'],
                'count'     => 20,
                'prize'     => 90.00
            ],
        ],
    ],

    // 前二 - 组选单式
    'QZU2_S'=> [
        'name'      => '前二单式',
        'group'     => 'EX',
        'row'       => 'zuxuan',
        'method'    => 'QZU2_S',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q'],
                'count'     => 20,
                'prize'     => 90.00
            ],
        ],
    ],

    // 前二 - 组选和值
    'QZU2HZ'=> [
        'name'      => '前二和值',
        'group'     => 'EX',
        'row'       => 'zuxuan',
        'method'    => 'QZU2HZ',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q'],
                'count'     => 20,
                'prize'     => 90.00
            ],
        ],
    ],

    // 前二 - 组选包胆
    'QZU2BD'=> [
        'name'      => '前二包胆',
        'group'     => 'EX',
        'row'       => 'zuxuan',
        'method'    => 'QZU2BD',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q'],
                'count'     => 20,
                'prize'     => 90.00
            ],
        ],
    ],

    // 定位胆 -  DWD 总控
    'DWD' => [
        'name'      => '定位胆',
        'group'     => 'DWD',
        'row'       => 'dingweidan',
        'method'    => 'DWD',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['w','q','b','s','g'],
                'count'     => 100,
                'prize'     => 18.00
            ],
        ],
        'expands' => [
            'name'  => '定位胆_{str}',
            'num'   => 1,
        ],
    ],

    // 定位胆 -  DWD 万
    'DWD_W' => [
        'name'      => '定位胆_万',
        'group'     => 'DWD',
        'hidden'    => true,
        'row'       => 'dingweidan',
        'method'    => 'DWD_W',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['w'],
                'count'     => 100,
                'prize'     => 18.00
            ],
        ],
    ],

    // 定位胆 -  DWD 千
    'DWD_Q' => [
        'name'      => '定位胆_千',
        'method'    => 'DWD_Q',
        'group'     => 'DWD',
        'hidden'    => true,
        'row'       => 'dingweidan',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['q'],
                'count'     => 100,
                'prize'     => 18.00
            ],
        ],
    ],

    // 定位胆 -  DWD 百
    'DWD_B' => [
        'name'      => '定位胆_百',
        'method'    => 'DWD_B',
        'group'     => 'DWD',
        'hidden'    => true,
        'row'       => 'dingweidan',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['w'],
                'count'     => 100,
                'prize'     => 18.00
            ],
        ],
    ],

    // 定位胆 -  DWD 十
    'DWD_S' => [
        'name'      => '定位胆_十',
        'method'    => 'DWD_S',
        'group'     => 'DWD',
        'hidden'    => true,
        'row'       => 'dingweidan',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['s'],
                'count'     => 100,
                'prize'     => 18.00
            ],
        ],
    ],

    // 定位胆 -  DWD 个
    'DWD_G' => [
        'name'      => '定位胆_个',
        'method'    => 'DWD_G',
        'group'     => 'DWD',
        'hidden'    => true,
        'row'       => 'dingweidan',
        'total'     => 1000,
        'levels'    => [
            '1' => [
                'position'  => ['g'],
                'count'     => 100,
                'prize'     => 18.00
            ],
        ],
    ],

    /** ====================== 不定位 ======================== */

    // 不定位 后三一码不定位
    'HBDW31' => [
        'name'      => '后三一码不定位',
        'group'     => 'BDW',
        'row'       => '3budingwei',
        'method'    => 'HBDW31',
        'total'     => 1000,
        'levels'    => [
            '1'     => [
                'position'  => ['b', 's', 'g'],
                'count'     => 271,
                'prize'     => 6.60
            ],
        ],
    ],

    // 不定位 - 前三一码不定位
    'QBDW31'=> [
        'name'      => '前三一码不定位',
        'group'     => 'BDW',
        'row'       => '3budingwei',
        'method'    => 'QBDW31',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b'],
                'count'     => 271,
                'prize'     => 6.60
            ],
        ],
    ],

    // 不定位 - 后三二码不定位
    'HBDW32'=> [
        'name'      => '后三二码不定位',
        'group'     => 'BDW',
        'row'       => '3budingwei',
        'method'    => 'HBDW32',
        'total'     => 1000,
        'levels' => [
            '1' => [
                'position'  => ['b','s','g'],
                'count'     => 54,
                'prize'     => 33.00
            ],
        ],
    ],

    // 不定位 - 前三二码不定位
    'QBDW32'=> [
        'name'      => '前三二码不定位',
        'group'     => 'BDW',
        'row'       => '3budingwei',
        'method'    => 'QBDW32',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b'],
                'count'     => 54,
                'prize'     => 33.00
            ],
        ],
    ],

    // 不定位 - 四星一码不定位
    'BDW41'=> [
        'name'      => '四星一码不定位',
        'group'     => 'BDW',
        'row'       => '4budingwei',
        'method'    => 'BDW41',
        'total'     => 10000,
        'levels' => [
            '1'=> [
                'position'  => ['q','b','s','g'],
                'count'     => 3439,
                'prize'     => 5.20
            ],
        ],
    ],

    // 不定位 - 四星二码不定位
    'BDW42'=> [
        'name'      => '四星二码不定位',
        'group'     => 'BDW',
        'row'       => '4budingwei',
        'method'    => 'BDW42',
        'total'     => 10000,
        'levels'    => [
            '1'=> [
                'position'  => ['q','b','s','g'],
                'count'     => 974,
                'prize'     => 18.40
            ],
        ],
    ],

    // 不定位 - 五星二码不定位
    'BDW52' => [
        'name'      => '五星二码不定位',
        'group'     => 'BDW',
        'row'       => '5budingwei',
        'method'    => 'BDW52',
        'total'     => 100000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b','s','g'],
                'count'     => 14670,
                'prize'     => 12.00
            ],
        ],
    ],

    // 不定位 - 五星三码不定位
    'BDW53'=> [
        'name'      => '五星三码不定位',
        'group'     => 'BDW',
        'row'       => '5budingwei',
        'method'    => 'BDW53',
        'total'     => 100000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b','s','g'],
                'count'     => 4350,
                'prize'     => 41.00
            ],
        ],
    ],

    /**  ================== 大小单双 ===============  */
    // 大小单双 - 前二大小单双
    'Q2DXDS'=> [
        'name'      => '前二大小单双',
        'group'     => 'DXDS',
        'row'       => 'dxds',
        'method'    => 'Q2DXDS',
        'total'     => 100,
        'levels' => [
            '1'=> [
                'position'  => ['w','q'],
                'count'     => 25,
                'prize'     => 7.20
            ],
        ],
    ],

    // 大小单双 - 后二大小单双
    'H2DXDS'=> [
        'name'      => '后二大小单双',
        'group'     => 'DXDS',
        'row'       => 'dxds',
        'method'    => 'H2DXDS',
        'total'     => 100,
        'levels' => [
            '1'=> [
                'position'  => ['s','g'],
                'count'     => 25,
                'prize'     => 7.20
            ],
        ],
    ],

    // 大小单双 - 前三大小单双
    'Q3DXDS'=> [
        'name'      => '前三大小单双',
        'group'     => 'DXDS',
        'row'       => 'dxds',
        'method'    => 'Q3DXDS',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b'],
                'count'     => 125,
                'prize'     => 14.40
            ],
        ],
    ],

    // 大小单双 - 后三大小单双
    'H3DXDS'=> [
        'name'      => '后三大小单双',
        'group'     => 'DXDS',
        'row'       => 'dxds',
        'method'    => 'H3DXDS',
        'total'     => 1000,
        'levels' => [
            '1'=> [
                'position'  => ['b', 's', 'g'],
                'count'     => 125,
                'prize'     => 14.40
            ],
        ],
    ],

    // 趣味 - 一帆风顺
    'YFFS'=> [
        'name'      => '一帆风顺',
        'group'     => 'QW',
        'row'       => 'quwei',
        'method'    => 'YFFS',
        'total'     =>100000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b','s','g'],
                'count'     => 40951,
                'prize'     => 4.30
            ],
        ],
    ],

    // 趣味 - 好事成双
    'HSCS'=> [
        'name'      => '好事成双',
        'group'     => 'QW',
        'row'       => 'quwei',
        'method'    => 'HSCS',
        'total'     => 100000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b','s','g'],
                'count'     => 8146,
                'prize'     => 22.00
            ],
        ],
    ],

    // 趣味 - 三星报喜
    'SXBX'=> [
        'name'      => '三星报喜',
        'group'     => 'QW',
        'row'       => 'quwei',
        'method'    => 'SXBX',
        'total'     => 100000,
        'levels' => [
            '1' => [
                'position'  => ['w','q','b','s','g'],
                'count'     => 856,
                'prize'     => 210.00
            ],
        ],
    ],

    // 趣味 - 四季发财
    'SJFC'=> [
        'name'      => '四季发财',
        'group'     => 'QW',
        'row'       => 'quwei',
        'method'    => 'SJFC',
        'total'     => 100000,
        'levels' => [
            '1'=> [
                'position'  => ['w','q','b','s','g'],
                'count'     => 46,
                'prize'     => 3900.00
            ],
        ],
    ],

    // 龙虎
    'LHWQ'=> [
        'name'      => '龙虎万千',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHWQ',
        'total'     => 100,
        'levels'    => [
            '1' => [
                'position'  => ['w', 'q'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['w', 'q'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['w','q'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],

    'LHWB'=> [
        'name'      => '龙虎万百',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHWB',
        'total'     => 100,
        'levels'    => [
            '1' => [
                'position'  => ['w', 'b'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['w', 'b'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['w','b'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],

    'LHWS'=> [
        'name'      => '龙虎万十',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHWS',
        'total'     => 100,
        'levels'    => [
            '1' => [
                'position'  => ['w', 's'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['w', 's'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['w', 's'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],

    'LHWG'=> [
        'name'      => '龙虎万个',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHWG',
        'total'     => 100,
        'levels' => [
            '1' => [
                'position'  => ['w', 'g'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['w', 'g'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['w', 'g'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],

    'LHQB'=> [
        'name'      => '龙虎千百',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHQB',
        'total'     => 100,
        'levels' => [
            '1' => [
                'position'  => ['q', 'b'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['q', 'b'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['q', 'b'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],

    'LHQS' => [
        'name'      => '龙虎千十',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHQS',
        'total'     => 100,
        'levels' => [
            '1' => [
                'position'  => ['q', 's'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['q', 's'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['q', 's'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],

    // 千个
    'LHQG'=> [
        'name'      => '龙虎千个',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHQG',
        'total'     => 100,
        'levels' => [
            '1' => [
                'position'  => ['q', 'g'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['q', 'g'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['q', 'g'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],

    'LHBS'=> [
        'name'      => '龙虎百十',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHBS',
        'total'     => 100,
        'levels' => [
            '1' => [
                'position'  => ['b', 's'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['b', 's'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['b', 's'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],

    'LHBG'=> [
        'name'      => '龙虎百个',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHBG',
        'total'     => 100,
        'levels' => [
            '1' => [
                'position'  => ['b', 'g'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['b', 'g'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['b', 'g'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],

    'LHSG' => [
        'name'      => '龙虎十个',
        'group'     => 'LH',
        'row'       => 'lhh',
        'method'    => 'LHSG',
        'total'     => 100,
        'levels' => [
            '1' => [
                'position'  => ['s', 'g'],
                'levelName' => '和',
                'count'     => 10,
                'prize'     => 18
            ],

            '2' => [
                'position'  => ['s', 'g'],
                'levelName' => '龙',
                'count'     => 45,
                'prize'     => 4
            ],

            '3' => [
                'position'  => ['s', 'g'],
                'levelName' => '虎',
                'count'     => 45,
                'prize'     => 4
            ],
        ],
    ],
];
