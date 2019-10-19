<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;

trait AuthTraitController {
  protected $_user = null;

  public function getUserByToken(Request $request) {
    if ($this->_user === null) {
      $token = $request->attributes->get('auth_token');
      $this->_user = $this->getDoctrine()->getRepository(User::class)->getUserByToken($token);
    }

    return $this->_user;
  }
}