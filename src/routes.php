<?php

return [
    '~^$~' => [\Test\Controllers\MainController::class, 'main'],

    '~^users/login$~' => [\Test\Controllers\UsersController::class, 'login'],
    '~^users/logout$~' => [\Test\Controllers\UsersController::class, 'logout'],
    '~^users/signUp~' => [\Test\Controllers\UsersController::class, 'signUp'],
    '~^users/(.*)$~' => [\Test\Controllers\UsersController::class, 'profile'],
    '~^users/(\d+)/activate/(.+)$~' => [\Test\Controllers\UsersController::class, 'activate'],

    '~^articles/(\d+)$~' => [\Test\Controllers\ArticlesController::class, 'view'],
    '~^articles/add$~' => [\Test\Controllers\ArticlesController::class, 'add'],
    '~^articles/(\d+)/edit$~' => [\Test\Controllers\ArticlesController::class, 'edit'],
    '~^articles/(\d+)/delete$~' => [\Test\Controllers\ArticlesController::class, 'delete'],
];