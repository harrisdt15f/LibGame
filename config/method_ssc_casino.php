<?php
// 时时彩类型 - 数字型
return [
    // 万
    'CO_ZX_W' => [
        'name'      => '第一球直选',
        'type'      => 'casino',
        'group'     => 'COD1Q',
        'row'       => '1q',
        'method'    => 'CO_ZX_W',
        'total' => 10,
        'levels' => [
            '1' => [
                'position'  => ['w'],
                'count'     => 1,
                'prize'     => 1.8
            ],
        ],
    ],

    'CO_ZX_W_DXDS' => [
        'name'      => '第一球大小单双',
        'type'      => 'casino',
        'group'     => 'COD1Q',
        'row'       => '1q',
        'method'    => 'CO_ZX_W_DXDS',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['w'],
                'count'     => 1,
                'prize'     => 3.6
            ],
        ],
    ],

    // 千
    'CO_ZX_Q' => [
        'name'      => '第二球直选',
        'type'      => 'casino',
        'group'     => 'COD2Q',
        'row'       => '2q',
        'method'    => 'CO_ZX_Q',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['q'],
                'count'     => 1,
                'prize'     => 1.8
            ],
        ],
    ],

    'CO_ZX_Q_DXDS' => [
        'name'      => '第二球大小单双',
        'type'      => 'casino',
        'group'     => 'COD2Q',
        'row'       => '2q',
        'method'    => 'CO_ZX_Q_DXDS',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['q'],
                'count'     => 1,
                'prize'     => 3.6
            ],
        ],
    ],

    // 百
    'CO_ZX_B' => [
        'name'      => '第三球直选',
        'type'      => 'casino',
        'group'     => 'COD3Q',
        'row'       => '3q',
        'method'    => 'CO_ZX_Q',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['b'],
                'count'     => 1,
                'prize'     => 1.8
            ],
        ],
    ],

    'CO_ZX_B_DXDS' => [
        'name'      => '第三球大小单双',
        'type'      => 'casino',
        'group'     => 'COD3Q',
        'row'       => '3q',
        'method'    => 'CO_ZX_Q_DXDS',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['b'],
                'count'     => 1,
                'prize'     => 3.6
            ],
        ],
    ],

    // 十
    'CO_ZX_S' => [
        'name'      => '第四球直选',
        'type'      => 'casino',
        'group'     => 'COD4Q',
        'row'       => '4q',
        'method'    => 'CO_ZX_S',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['s'],
                'count'     => 1,
                'prize'     => 1.8
            ],
        ],
    ],

    'CO_ZX_S_DXDS' => [
        'name'      => '第四球大小单双',
        'type'      => 'casino',
        'group'     => 'COD4Q',
        'row'       => '4q',
        'method'    => 'CO_ZX_S_DXDS',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['s'],
                'count'     => 1,
                'prize'     => 3.6
            ],
        ],
    ],

    // 个
    'CO_ZX_G' => [
        'name'      => '第五球直选',
        'type'      => 'casino',
        'group'     => 'COD5Q',
        'row'       => '5q',
        'method'    => 'CO_ZX_G',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['g'],
                'count'     => 1,
                'prize'     => 1.8
            ],
        ],
    ],

    'CO_ZX_G_DXDS' => [
        'name'      => '第五球大小单双',
        'type'      => 'casino',
        'group'     => 'COD5Q',
        'row'       => '5q',
        'method'    => 'CO_ZX_G_DXDS',
        'total'     => 10,
        'levels' => [
            '1' => [
                'position'  => ['g'],
                'count'     => 1,
                'prize'     => 3.6
            ],
        ],
    ],

    // 总和
    'CO_ZHDXDS' => [
        'name'      => '总和',
        'type'      => 'casino',
        'group'     => 'COZH',
        'row'       => 'zhdxds',
        'method'    => 'CO_ZHDXDS',
        'total'     => 100000,
        'levels'    => [
            '1' => [
                'position'  => ['w', 'q', 'b', 's', 'g'],
                'count'     => 25000,
                'prize'     => 3.6
            ],
        ],
    ],

    // 龙虎
    'CO_LHWQ' => [
        'name'      => '龙虎万千',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHWQ',
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

    'CO_LHWB' => [
        'name'      => '龙虎万百',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHWB',
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

    'CO_LHWS' => [
        'name'      => '龙虎万十',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHWS',
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

    'CO_LHWG' => [
        'name'      => '龙虎万个',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHWG',
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

    'CO_LHQB' => [
        'name'      => '龙虎千百',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHQB',
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

    'CO_LHQS' => [
        'name'      => '龙虎千十',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHQS',
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
    'CO_LHQG' => [
        'name'      => '龙虎千个',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHQG',
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

    'CO_LHBS' => [
        'name'      => '龙虎百十',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHBS',
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

    'CO_LHBG' => [
        'name'      => '龙虎百个',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHBG',
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

    'CO_LHSG' => [
        'name'      => '龙虎十个',
        'group'     => 'COLH',
        'row'       => 'lhh',
        'method'    => 'CO_LHSG',
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

    // 全五中一
    'CO_QWZY' => [
        'name'      => '全五中一',
        'type'      => 'casino',
        'group'     => 'COQWZY',
        'row'       => 'qwzy',
        'method'    => 'CO_QWZY',
        'total'     => 100000,
        'levels'    => [
            '1' => [
                'position'  => ['w', 'q', 'b', 's', 'g'],
                'count'     => 40951,
                'prize'     => 4.3955
            ],
        ],
    ],
];
