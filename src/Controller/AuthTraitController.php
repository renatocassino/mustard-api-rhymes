<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;

trait AuthTraitController {
  protected $_user = null;

  public function getUserByToken(Request $request) {
    if ($this->_user === null) {
      $token = $request->attributes->get('auth_token');
      $user = $this->getDoctrine()->getRepository(User::class)->getUserByToken($token);
      $this->_user = new User();
      $this->_user->setByObj($user);
    }

    return $this->_user;
  }
}