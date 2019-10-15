<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RhymesController extends AbstractController
{
    /**
     * @Route("/api/v1/rhymes/{language}/{word}")
     */
    public function words($language, $word)
    {
        if ($language !== 'pt-br' && $language !== 'en') {
            return $this->json(['error' => 'Must have language pt-br or en'], 404, []);
        }

        $allWords = explode("\n", file_get_contents(dirname(__FILE__) . "/data/$language.txt"));
        $size = isset($_GET['size'])
            ? (int) $_GET['size']
            : 3;

        $wordToSearch = substr($word, $size * -1);
        $words = array_values(array_filter($allWords, function($w) use ($wordToSearch) {
            return preg_match("/$wordToSearch$/", $w);
        }));

        sort($words);

        $content = [
            'data' => [
                'language' => $language,
                'words' => $words,
            ],
        ];

        return $this->json(
            $content,
            200,
            ['Access-Control-Allow-Origin' => '*']
        );
    }
}
