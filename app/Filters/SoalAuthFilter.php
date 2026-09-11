<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SoalAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('is_logged_in')) {
            return redirect()->to(base_url('auth/login'));
        }

        // Check if secondary password has been verified
        if (!$session->get('soal_auth_verified')) {
            $uri = uri_string();
            return redirect()->to(base_url('auth/verifyPassword?next=' . urlencode($uri)));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
