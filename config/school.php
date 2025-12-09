<?php

return [

    /*
    |--------------------------------------------------------------------------
    | School Information (ALL UNUSED)
    |--------------------------------------------------------------------------
    */
    'name' => 'Jujutsu High',
    'current_year' => '2024/2025',
    'year_start' => '09-01',  // mm-dd
    'year_end' => '06-30',


    /*
    |--------------------------------------------------------------------------
    | Class / Edugroup Settings (ALL UNUSED)
    |--------------------------------------------------------------------------
    */
    'class' => [
        'roman_levels' => [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX',
        ],
        'allow_multiple_core_classes' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Economy: XP, Bucks, Prices, Cosmetics
    |--------------------------------------------------------------------------
    */
    'economy' => [
        'xp_per_level' => 100,
//        'bucks_name' => 'Brain Bucks',

        'prices' => [
            'rename' => 40,
            'profile_picture' => 10,
            'profile_picture_random' => 5,
            'background' => 15,
            'background_random' => 10,
            'theme' => 30,
//            'username_effect' => 25,
        ],
    ],

    'cosmetics' => [
        // route is assets/img/avatars/rGveAS.png
        'avatars' => ['YOUR-TEXT.png', 'rGveAS.png', 'OeDViA.png', 'DLPmhZ.png', 'yLoXuU.png', 'YrYSjq.png', 'nJcYpD.png', 'zEsitk.png', 'hubXRT.png', 'XJpOQk.png', 'pXbWcC.png', 'QxTnYF.png', 'wCmjvH.png', 'VfNqZd.png', 'XDREbx.png', 'rHrxMB.png', 'WgXhoB.png', 'kCQBoz.png', 'wzUMts.png', 'mJqfKN.png', 'JvhsPA.png', 'SgLDnY.png', 'xTOvaT.png', 'aKJdRu.png',],
        // route is assets/img/backgrounds/pattern1.png
        'backgrounds' => ['pattern1.png', 'pattern2.png', 'pattern3.png', 'pattern4.png', 'pattern5.png', 'pattern6.png', 'pattern7.png', 'pattern8.png', 'pattern9.png', 'pattern10.png', 'pattern11.png', 'pattern12.png', 'pattern13.png', 'pattern14.png', 'pattern15.png', 'pattern16.png', 'pattern17.png', 'pattern18.png'],
        // themes are defined in app.css as .bg-gradient-dark
        'themes' => ['dark', 'magenta', 'sky', 'light', 'yellow', 'green']
    ],

];
