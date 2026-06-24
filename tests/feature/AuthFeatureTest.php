<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\AppTestCase;

/**
 * Blackbox: Auth controller (login page, login attempts, logout)
 *
 * @internal
 */
final class AuthFeatureTest extends AppTestCase
{
    use FeatureTestTrait;

    public function testLoginUrlRendersSuccessfully(): void
    {
        $result = $this->get('login');

        $result->assertOK();
        $result->assertSee('Masuk');
        $result->assertSee('Username');
        $result->assertSee('Kata sandi');
    }

    public function testAttemptWithCorrectCredentialsRedirectsToDashboard(): void
    {
        $result = $this->post('login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $result->assertRedirect();
        $this->assertStringContainsString('dashboard', strtolower($result->getRedirectUrl() ?? ''));
        $result->assertSessionHas('username', 'admin');
        $result->assertSessionHas('user_role', 'admin');
    }

    public function testAttemptWithIncorrectCredentialsRedirectsBack(): void
    {
        $result = $this->post('login', [
            'username' => 'admin',
            'password' => 'wrongpassword',
        ]);

        $result->assertRedirect();
        $result->assertSessionMissing('username');
        $result->assertSessionHas('error', 'Username atau kata sandi salah, atau akun tidak aktif.');
    }

    public function testLogoutClearsSessionAndRedirectsToLogin(): void
    {
        $result = $this->sessionAsAdmin()->get('logout');

        $result->assertRedirect();
        $this->assertStringContainsString('login', strtolower($result->getRedirectUrl() ?? ''));
        $result->assertSessionHas('message', 'Anda telah keluar.');
    }
}
