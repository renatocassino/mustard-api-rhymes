<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

interface AuthenticatedController {
  public function getUserByToken(Request $request);
}
