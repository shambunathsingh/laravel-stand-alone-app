<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// require base_path('app/Modules/BaseTemplate/routes.php');

foreach (glob(base_path('app/Modules/*/routes.php')) as $moduleRoutes) {
    require $moduleRoutes;
}


