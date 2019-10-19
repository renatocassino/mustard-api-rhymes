<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Lyric;

class LyricsController extends AbstractController implements AuthenticatedController {
  use AuthTraitController;

  /**
   * @Route("/api/v1/lyrics")
   */
  public function listLyrics(Request $request) {
    $user = $this->getUserByToken($request);
    $lyrics = $this->getDoctrine()
      ->getRepository(Lyric::class)
      ->findAll();

    return $this->json([
      'user' => $user,
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
