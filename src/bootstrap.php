<?php

if (!request()->ajax()) {

    $lock_file = base_path('composer.lock');

    if (is_file($lock_file)) {

        $lock = file_get_contents($lock_file);
        $json = json_decode($lock, 1);
        $admin = collect($json['packages'])->where('name', 'lar/lte-admin')->first();
        if ($admin && isset($admin['version'])) {

            \Lar\LteAdmin\LteAdmin::$vesion = $admin['version'];
        }
    }
}