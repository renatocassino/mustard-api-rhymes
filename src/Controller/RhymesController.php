<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
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

        $page = isset($_GET['page'])
            ? (int) $_GET['page']
            : 1;
        
        $limit = isset($_GET['limit'])
            ? (int) $_GET['limit']
            : 30;

        $offset = ($page - 1) * $limit;

        $wordToSearch = substr($word, $size * -1);
        $words = array_values(array_filter($allWords, function($w) use ($wordToSearch) {
            return preg_match("/$wordToSearch$/", $w);
        }));

        sort($words);

        $total = count($words);

        $next = "/api/v1/rhymes/$language/$word?page=" . ($page + 1) . "&limit=$limit";
        if (($page + 1) * $limit > $total) {
            $next = null;
        }

        $last = "/api/v1/rhymes/$language/$word?page=" . ceil($total / $limit) . "&limit=$limit";
        if ($total === 0) {
            $last = null;
        }

        $content = [
            'links' => [
                'self' => "/api/v1/rhymes/$language/$word?page=$page&limit=$limit",
                'next' => $next,
                'last' => $last,
            ],
            'data' => [
                'language' => $language,
                'words' => array_slice($words, $offset, $limit),
            ],
        ];

        return $this->json(
            $content,
            200,
            ['Access-Control-Allow-Origin' => '*']
        );
    }
}
