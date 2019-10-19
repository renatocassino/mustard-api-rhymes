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
          'id' => $lyric->getId(),
          'title' => $lyric->getTitle(),
          'lyric' => $lyric->getLyric(),
          'createdAt' => $lyric->getCreatedAt(),
          'updatedAt' => $lyric->getUpdatedAt(),
        ];
      }, $lyrics)
    ]);
  }

  /**
   * @Route("/api/v1/lyrics/{id}")
   */
  public function viewLyric(Request $request, $id) {
    $user = $this->getUserByToken($request);

    $lyric = $this->getDoctrine()
      ->getRepository(Lyric::class)
      ->findOneBy([
        'user' => $user->id,
        'id' => $id,
      ]);

    if (!$lyric) {
      return $this->json([
        'error' => 'Cannot find this lyric :/'
      ], 404);
    }

    return $this->json([
      'data' => [
        'id' => $lyric->getId(),
        'title' => $lyric->getTitle(),
        'lyric' => $lyric->getLyric(),
        'createdAt' => $lyric->getCreatedAt(),
        'updatedAt' => $lyric->getUpdatedAt(),
      ]
    ]);
  }
}
