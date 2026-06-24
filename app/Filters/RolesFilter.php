<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RolesFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userId = session()->get('user_id');
        if (! $userId) {
            return redirect()->to('/login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        // CI4 melewatkan setiap token setelah ':' sebagai elemen terpisah di $arguments.
        // Contoh: 'roles:admin,petugas' → $arguments = ['admin', 'petugas']
        $allowed = array_filter(array_map('trim', (array) ($arguments ?? [])));

        if ($allowed === []) {
            return null;
        }

        $role = (string) session()->get('user_role');

        if (! in_array($role, $allowed, true)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
