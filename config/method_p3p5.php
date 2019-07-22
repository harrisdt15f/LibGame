<?php

return [
    // 排三直选复式
    'QZX3' => [
        'name' => '直选复式',
        'group' => 'P3',
        'row' => 'zhixuan',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'count' => 1,
                'prize' => 1800.00
            ],
        ],
    ],

    'QZX3_S' => [
        'name' => '直选单式',
        'group' => 'P3',
        'row' => 'zhixuan',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'count' => 1,
                'prize' => 1800.00
            ],
        ],
    ],

    'QZXHZ' => [
        'name' => '直选和值',
        'group' => 'P3',
        'row' => 'zhixuan',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'count' => 1,
                'prize' => 1800.00
            ],
        ],
    ],

    'QZU3' => [
        'name' => '组三复式',
        'group' => 'P3',
        'row' => 'zuxuan',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'count' => 3,
                'prize' => 600.00
            ],
        ],
    ],

    'QZU3_S' => [
        'name' => '组三单式',
        'group' => 'P3',
        'row' => 'zuxuan',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'count' => 3,
                'prize' => 600.00
            ],
        ],
    ],

    // 排三组六
    'QZU6' => [
        'name' => '组六复式',
        'group' => 'P3',
        'row' => 'zuxuan',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'count' => 6,
                'prize' => 300.00
            ],
        ],
    ],

    'QZU6_S' => [
        'name' => '组六单式',
        'group' => 'P3',
        'row' => 'zuxuan',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'count' => 6,
                'prize' => 300.00
            ],
        ],
    ],

    'QHHZX' => [
        'name' => '混合组选',
        'group' => 'P3',
        'row' => 'zuxuan',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'levelName' => '组三',
                'count' => 3,
                'prize' => 600.00
            ],

            '2' => [
                'position' => ['w', 'q', 'b'],
                'levelName' => '组六',
                'count' => 6,
                'prize' => 300.00
            ],
        ],
    ],

    'QZUHZ' => [
        'name' => '组选和值',
        'group' => 'P3',
        'row' => 'zuxuan',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'levelName' => '组三',
                'count' => 3,
                'prize' => 600.00
            ],
            '2' => [
                'position' => ['w', 'q', 'b'],
                'levelName' => '组六',
                'count' => 6,
                'prize' => 300.00
            ],
        ],
    ],

    /**   ====== 二星 ====== */
    'QZX2' => [
        'name' => '(前二)排五直选复式',
        'group' => 'EX',
        'row' => 'zhixuan',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['w', 'q'],
                'count' => 1,
                'prize' => 180.00
            ],
        ],
    ],
    'QZX2_S' => [
        'name' => '(前二)排五直选单式',
        'group' => 'EX',
        'row' => 'zhixuan',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['w', 'q'],
                'count' => 1,
                'prize' => 180.00
            ],
        ],
    ],

    'HZX2' => [
        'name' => '(后二)排五直选复式',
        'group' => 'EX',
        'row' => 'zhixuan',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['s', 'g'],
                'count' => 1,
                'prize' => 180.00
            ],
        ],
    ],
    'HZX2_S' => [
        'name' => '(后二)排五直选单式',
        'group' => 'EX',
        'row' => 'zhixuan',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['s', 'g'],
                'count' => 1,
                'prize' => 180.00
            ],
        ],
    ],

    'QZU2' => [
        'name' => '(前二)排五组选复式',
        'method' => 'ZU2',
        'group' => 'EX',
        'row' => 'zuxuan',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['w', 'q'],
                'count' => 2,
                'prize' => 90.00
            ],
        ],
    ],

    'QZU2_S' => [
        'name' => '(前二)排五组选单式',
        'group' => 'EX',
        'row' => 'zuxuan',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['w', 'q'],
                'count' => 2,
                'prize' => 90.00
            ],
        ],
    ],

    'HZU2' => [
        'name' => '(后二)排五组选复式',
        'group' => 'EX',
        'row' => 'zuxuan',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['s', 'g'],
                'count' => 2,
                'prize' => 90.00
            ],
        ],
    ],

    'HZU2_S' => [
        'name' => '(后二)排五组选单式',
        'group' => 'EX',
        'row' => 'zuxuan',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['s', 'g'],
                'count' => 2,
                'prize' => 90.00
            ],
        ],
    ],

    // 定位胆
    'DWD' => [
        'name' => '定位胆',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b', 's', 'g'],
                'count' => 10,
                'prize' => 18.00
            ],
        ],
    ],

    // 定位胆 - 万
    'DWD_W' => [
        'name' => '定位胆_万',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'total' => 10,
        'hidden' => true,
        'levels' => [
            '1' => [
                'position' => ['w'],
                'count' => 1,
                'prize' => 18.00
            ],
        ],
    ],

    // 定位胆 - 千
    'DWD_Q' => [
        'name' => '定位胆_千',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'total' => 10,
        'hidden' => true,
        'levels' => [
            '1' => [
                'position' => ['q'],
                'count' => 1,
                'prize' => 18.00
            ],
        ],
    ],

    // 定位胆 - 百
    'DWD_B' => [
        'name' => '定位胆_百',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'total' => 10,
        'hidden' => true,
        'levels' => [
            '1' => [
                'position' => ['b'],
                'count' => 1,
                'prize' => 18.00
            ],
        ],
    ],

    // 定位胆 - 十
    'DWD_S' => [
        'name' => '定位胆_十',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'total' => 10,
        'hidden' => true,
        'levels' => [
            '1' => [
                'position' => ['s'],
                'count' => 1,
                'prize' => 18.00
            ],
        ],
    ],

    // 定位胆 - 个
    'DWD_G' => [
        'name' => '定位胆_个',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'total' => 10,
        'hidden' => true,
        'levels' => [
            '1' => [
                'position' => ['g'],
                'count' => 1,
                'prize' => 18.00
            ],
        ],
    ],

    // 不定位 - 一码
    'QBDW31' => [
        'name' => '前三一码不定位',
        'group' => 'BDW',
        'row' => 'budingwei',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'count' => 271,
                'prize' => 6.60
            ],
        ],
    ],

    'QBDW32' => [
        'name' => '前三二码不定位',
        'group' => 'BDW',
        'row' => 'budingwei',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q', 'b'],
                'count' => 54,
                'prize' => 33.00
            ],
        ],
    ],

    // 大小单双
    'Q2DXDS' => [
        'name' => '前二大小单双',
        'group' => 'DXDS',
        'row' => 'dxds',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['w', 'q'],
                'count' => 250,
                'prize' => 7.20
            ],
        ],
    ],

    'H2DXDS' => [
        'name' => '后二大小单双',
        'group' => 'DXDS',
        'row' => 'dxds',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['s', 'g'],
                'count' => 250,
                'prize' => 7.20
            ],
        ],
    ],
];
