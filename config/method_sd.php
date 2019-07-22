<?php
// 时时乐 三D
return [
    // 直选复式
    'QZX3' => [
        'name' => '直选复式',
        'group' => 'SX',
        'row' => 'zhixuan',
        'method' => 'QZX3',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 1,
                'prize' => 1800.00
            ],
        ],
    ],

    // 直选单式
    'QZX3_S' => [
        'name' => '直选单式',
        'group' => 'SX',
        'row' => 'zhixuan',
        'method' => 'QZX3_S',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 1,
                'prize' => 1800.00
            ],
        ],
    ],

    'QZXHZ' => [
        'name' => '直选和值',
        'group' => 'SX',
        'row' => 'zhixuan',
        'method' => 'QZXHZ',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 1,
                'prize' => 1800.00
            ],
        ],
    ],

    'QZU3' => [
        'name' => '组三',
        'group' => 'SX',
        'row' => 'zuxuan',
        'method' => 'QZU3',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 3,
                'prize' => 600.00
            ],
        ],
    ],

    'QZU3_S' => [
        'name' => '组三单式',
        'group' => 'SX',
        'row' => 'zuxuan',
        'method' => 'QZU3_S',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 3,
                'prize' => 600.00
            ],
        ],
    ],

    'QZU6' => [
        'name' => '组六',
        'group' => 'SX',
        'row' => 'zuxuan',
        'method' => 'QZU6',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 6,
                'prize' => 300.00
            ],
        ],
    ],

    'QZU6_S' => [
        'name' => '组六单式',
        'group' => 'SX',
        'row' => 'zuxuan',
        'method' => 'QZU6_S',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 6,
                'prize' => 300.00
            ],
        ],
    ],

    'QHHZX' => [
        'name' => '混合组选',
        'group' => 'SX',
        'row' => 'zuxuan',
        'method' => 'QHHZX',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'levelName' => '组三',
                'count' => 3,
                'prize' => 600.00
            ],
            '2' => [
                'position' => ['b', 's', 'g'],
                'levelName' => '组六',
                'count' => 6,
                'prize' => 300.00
            ],
        ],
    ],

    'QZUHZ' => [
        'name' => '组选和值',
        'group' => 'SX',
        'row' => 'zuxuan',
        'method' => 'QZUHZ',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'levelName' => '组三',
                'count' => 3,
                'prize' => 600.00
            ],
            '2' => [
                'position' => ['b', 's', 'g'],
                'levelName' => '组六',
                'count' => 6,
                'prize' => 300.00
            ],
        ],
    ],

    'QZU3BD' => [
        'name' => '组选包胆',
        'group' => 'SX',
        'row' => 'zuxuan',
        'method' => 'QZU3BD',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'levelName' => '组三',
                'count' => 3,
                'prize' => 600.00
            ],
            '2' => [
                'position' => ['b', 's', 'g'],
                'levelName' => '组六',
                'count' => 6,
                'prize' => 300.00
            ],
        ],
    ],

    'QZX2' => [
        'name' => '前二直选复式',
        'group' => 'EX',
        'row' => 'zhixuan',
        'method' => 'QZX2',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['b', 's'],
                'count' => 10,
                'prize' => 180.00
            ],
        ],
    ],

    'QZX2_S' => [
        'name' => '前二直选单式',
        'group' => 'EX',
        'row' => 'zhixuan',
        'method' => 'QZX2_S',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['b', 's'],
                'count' => 10,
                'prize' => 180.00
            ],
        ],
    ],

    'HZX2' => [
        'name' => '后二直选复式',
        'group' => 'EX',
        'row' => 'zhixuan',
        'method' => 'HZX2',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['s', 'g'],
                'count' => 10,
                'prize' => 180.00
            ],
        ],
    ],

    'HZX2_S' => [
        'name' => '后二直选单式',
        'group' => 'EX',
        'row' => 'zhixuan',
        'method' => 'HZX2_S',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['s', 'g'],
                'count' => 10,
                'prize' => 180.00
            ],
        ],
    ],

    'HZU2' => [
        'name' => '后二组选',
        'group' => 'EX',
        'row' => 'zuxuan',
        'method' => 'HZU2',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['s', 'g'],
                'count' => 20,
                'prize' => 90.00
            ],
        ],
    ],

    'HZU2_S' => [
        'name' => '后二组选单式',
        'group' => 'EX',
        'row' => 'zuxuan',
        'method' => 'HZU2_S',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['s', 'g'],
                'count' => 20,
                'prize' => 90.00
            ],
        ],
    ],

    'QZU2' => [
        'name' => '前二组选',
        'group' => 'EX',
        'row' => 'zuxuan',
        'method' => 'QZU2',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['b', 's'],
                'count' => 20,
                'prize' => 90.00
            ],
        ],
    ],

    'QZU2_S' => [
        'name' => '前二组选单式',
        'group' => 'EX',
        'row' => 'zuxuan',
        'method' => 'QZU2_S',
        'total' => 100,
        'levels' => [
            '1' => [
                'position' => ['b', 's'],
                'count' => 20,
                'prize' => 90.00
            ],
        ],
    ],

    'DWD' => [
        'name' => '定位胆',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'method' => 'DWD',
        'total' => 10,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 1,
                'prize' => 18.00
            ],
        ]
    ],

    // 定位胆 DWD
    'DWD_B' => [
        'name' => '定位胆',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'method' => 'DWD_B',
        'total' => 10,
        'hidden' => true,
        'levels' => [
            '1' => [
                'position' => ['b'],
                'count' => 1,
                'prize' => 18.00
            ],
        ]
    ],

    // 定位胆 DWD
    'DWD_S' => [
        'name' => '定位胆',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'method' => 'DWD_S',
        'total' => 10,
        'hidden' => true,
        'levels' => [
            '1' => [
                'position' => ['s'],
                'count' => 1,
                'prize' => 18.00
            ],
        ]
    ],
    // 定位胆 DWD
    'DWD_G' => [
        'name' => '定位胆',
        'group' => 'DWD',
        'row' => 'dingweidan',
        'method' => 'DWD_G',
        'total' => 10,
        'hidden' => true,
        'levels' => [
            '1' => [
                'position' => ['g'],
                'count' => 1,
                'prize' => 18.00
            ],
        ]
    ],

    // 不定位
    'QBDW31' => [
        'name' => '一码不定位',
        'group' => 'BDW',
        'row' => 'budingwei',
        'method' => 'QBDW31',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 271,
                'prize' => 6.60
            ],
        ],
    ],

    'QBDW32' => [
        'name' => '二码不定位',
        'group' => 'BDW',
        'row' => 'budingwei',
        'method' => 'QBDW32',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's', 'g'],
                'count' => 54,
                'prize' => 33.00
            ],
        ],
    ],

    'Q2DXDS' => [
        'name' => '前二大小单双',
        'group' => 'DXDS',
        'row' => 'dxds',
        'method' => 'Q2DXDS',
        'total' => 1000,
        'levels' => [
            '1' => [
                'position' => ['b', 's'],
                'count' => 250,
                'prize' => 7.20
            ],
        ],
    ],

    'H2DXDS' => [
        'name' => '后二大小单双',
        'group' => 'DXDS',
        'row' => 'dxds',
        'method' => 'H2DXDS',
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
