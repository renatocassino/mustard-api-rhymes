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
   * @Route("/api/v1/lyrics", methods={"GET","HEAD"})
   */
  public function listLyrics(Request $request) {
    $user = $this->getUserByToken($request);
    // $userDB = $this->getDoctrine()
    //   ->getRepository(User::class)
    //   ->find($user->getId());

    // $lyrics = $userDB->getLyrics();
    $lyrics = $this->getDoctrine()
      ->getRepository(Lyric::class)
      ->findBy([
        'user' => $user->getId(),
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
    ], 200, ['Access-Control-Allow-Origin' => '*']);
  }

  /**
   * @Route("/api/v1/lyrics/{id}", methods={"GET","HEAD"})
   */
  public function viewLyric(Request $request, $id) {
    $user = $this->getUserByToken($request);

    $lyric = $this->getDoctrine()
      ->getRepository(Lyric::class)
      ->findOneBy([
        'user' => $user->getId(),
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
      ], 200, ['Access-Control-Allow-Origin' => '*']);
  }

  /**
   * @Route("/api/v1/lyrics", methods={"POST"})
   */
  public function createLyric(Request $request) {
    $userJWT = $this->getUserByToken($request);
    $user = $this->getDoctrine()->getRepository(User::class)->find($userJWT->getId());
    $data = json_decode($request->getContent());

    $entityManager = $this->getDoctrine()->getEntityManager();

    $lyric = new Lyric();
    $lyric->setUser($user);
    $lyric->setTitle($data->title);
    $lyric->setLyric($data->lyric);
    $lyric->setCreatedAt(new \DateTime());
    $lyric->setUpdatedAt(new \DateTime());

    $entityManager->persist($lyric);
    $entityManager->flush();

    return $this->json([
      'data' => [
        'id' => $lyric->getId(),
        'title' => $lyric->getTitle(),
        'lyric' => $lyric->getLyric(),
        'createdAt' => $lyric->getCreatedAt(),
        'updatedAt' => $lyric->getUpdatedAt(),
      ]
      ], 200, ['Access-Control-Allow-Origin' => '*']);
  }

  /**
   * @Route("/api/v1/lyrics/{id}", methods={"PUT", "OPTIONS"})
   */
  public function updateLyric(Request $request, $id) {
    $user = $this->getUserByToken($request);
    $data = json_decode($request->getContent());

    $entityManager = $this->getDoctrine()->getEntityManager();

    $lyric = $this->getDoctrine()->getRepository(Lyric::class)->find($id);
    if ($lyric->getUser()->getId() !== $user->getId()) {
      return $this->json([
        'error' => 'You cannot edit another lyric',
      ], 403);
    }

    $lyric->setTitle($data->title);
    $lyric->setLyric($data->lyric);
    $lyric->setUpdatedAt(new \DateTime());

    $entityManager->persist($lyric);
    $entityManager->flush();

    return $this->json([
      'data' => [
        'id' => $lyric->getId(),
        'title' => $lyric->getTitle(),
        'lyric' => $lyric->getLyric(),
        'createdAt' => $lyric->getCreatedAt(),
        'updatedAt' => $lyric->getUpdatedAt(),
      ]
      ], 200, ['Access-Control-Allow-Origin' => '*']);
  }


  /**
   * @Route("/api/v1/lyrics/{id}", methods={"DELETE", "OPTIONS"})
   */
  public function deleteLyric(Request $request, $id) {
    $user = $this->getUserByToken($request);
    $data = json_decode($request->getContent());

    $entityManager = $this->getDoctrine()->getEntityManager();

    $lyric = $this->getDoctrine()->getRepository(Lyric::class)->find($id);
    if ($lyric->getUser()->getId() !== $user->getId()) {
      return $this->json([
        'error' => 'You cannot edit another lyric',
      ], 403);
    }

    $entityManager->remove($lyric);
    $entityManager->flush();

    return $this->json([], 204, ['Access-Control-Allow-Origin' => '*']);
  }
}
