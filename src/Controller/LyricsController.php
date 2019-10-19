<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Lyric;
use App\Entity\User;

class LyricsController extends AbstractController implements AuthenticatedController {
  use AuthTraitController;

  /**
   * @Route("/api/v1/lyrics")
   */
  public function listLyrics(Request $request) {
    $user = $this->getUserByToken($request);
    // $userDB = $this->getDoctrine()
    //   ->getRepository(User::class)
    //   ->find($user->id);

    // $lyrics = $userDB->getLyrics();
    $lyrics = $this->getDoctrine()
      ->getRepository(Lyric::class)
      ->findBy([
        'user' => $user->id,
      ]);

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
