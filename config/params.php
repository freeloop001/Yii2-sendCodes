<?php

return [
    'adminEmail' => '492713134@qq.com',
    'codeLogs' => [
        'search_dir' => 'D:/WWW/git/basic',
        'type' => ['php'],
        'max_size' => 0,
        'min_size' => 0,
        'max_create_time' => 0,
        'min_create_time' => 0,
        'max_update_time' => 0,
        'min_update_time' => strtotime(date('Y-m-d')),
        'filter_name' => []
    ],
    'codeZip' => [
        'save_dir' => 'D:/WWW/work_logs/',
        'file_name' => date('Y-m-d').'-code.zip'
    ],
    'codeEmail' => [
        'from' => '492713134@qq.com' ,
        'to' => '492713134@qq.com' ,
        'subject' => date('Y年m月d日').'当天代码',
        'textBody' => '' ,
        'htmlBody' => '' ,
    ]
];
