<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/healthcheck', function () use ($router) {
    return $router->app->version();
});

$router->get('/api/v1/words/{language}/{word}', function($language, $word) use ($router) {
    if ($language !== 'pt-br' && $language !== 'en') {
        return response()->json(['error' => 'Must have language pt-br or en'], 404, []);
    }

    $allWords = explode("\n", file_get_contents("../database/seeds/words/$language.txt"));
    $size = isset($_GET['size'])
        ? (int) $_GET['size']
        : 3;

    $wordToSearch = substr($word, $size * -1);

    return [
        'data' => [
            'language' => $language,
            'words' => array_values(array_filter($allWords, function($w) use ($wordToSearch) {
                return preg_match("/$wordToSearch$/", $w);
            })),
        ],
    ];
});
