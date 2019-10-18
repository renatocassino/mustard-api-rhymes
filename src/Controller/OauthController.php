<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;

class OauthController extends AbstractController {
  private function getClient() {
    $client = new \Google_Client();
    $client->setAuthConfig(dirname(__FILE__) . '/../../config/google.json');
    // $redirectUri = 'http://api.mustardplayer.io/auth/callback';
    $redirectUri = 'http://' . $_SERVER['HTTP_HOST'] . '/auth/callback';
    $client->setRedirectUri($redirectUri);
    return $client;
  }

  /**
   * @Route("/auth")
   */
  public function auth() {
    $client = $this->getClient();
    $client->addScope(\Google_Service_Plus::PLUS_ME);
    $client->addScope(\Google_Service_Plus::USERINFO_EMAIL);
    $client->addScope(\Google_Service_Plus::USERINFO_PROFILE);

    $authUrl = $client->createAuthUrl();
    return $this->redirect($authUrl);
  }

  /**
   * @Route("/auth/callback")
   */
  public function authCallback() {
    if (!isset($_GET['code'])) {
      return $this->redirect('/?error');
    }

    $client = $this->getClient();
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $accessToken = $token['access_token'];
    $bearerToken = $token['id_token'];
    $client->setAccessToken($token);

    $request = new Client();
    $response = $request->get('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $accessToken);
    $data = $response->json();

    $user = $this->getDoctrine()
      ->getRepository(User::class)
      ->createOrUpdate($data);

    $key = "example_key";
    $token = array(
        "iss" => "http://example.org",
        "aud" => "http://example.com",
        'email' => $user->getEmail(),
        "iat" => 1356999524,
        "nbf" => 1357000000
    );

    return $this->json([
      'token' => JWT::encode($token, $key),
    ]);
  }
}