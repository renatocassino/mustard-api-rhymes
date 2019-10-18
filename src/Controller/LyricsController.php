<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Lyric;

class LyricsController extends AbstractController {
  /**
   * @Route("/api/v1/lyrics")
   */
  public function listLyrics() {
    $lyrics = $this->getDoctrine()
      ->getRepository(Lyric::class)
      ->findAll();

    return $this->json([
      'data' => array_map(function($lyric) {
        return [
          'title' => $lyric->getTitle(),
          'lyric' => $lyric->getLyric(),
          'createdAt' => $lyric->getCreatedAt(),
          'updatedAt' => $lyric->getUpdatedAt(),
        ];
      }, $lyrics)
    ]);
  }
}
