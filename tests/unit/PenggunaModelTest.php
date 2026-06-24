<?php

namespace Tests\Unit;

use App\Models\PenggunaModel;
use Tests\Support\AppTestCase;

/**
 * Whitebox: PenggunaModel (findByUsername, hashPassword, verifyPassword, validation)
 *
 * @internal
 */
final class PenggunaModelTest extends AppTestCase
{
    public function testFindByUsernameReturnsUserData(): void
    {
        $model = new PenggunaModel();
        $user  = $model->findByUsername('admin');

        $this->assertNotNull($user);
        $this->assertSame('Administrator', $user['nama_pengguna']);
        $this->assertSame('admin', $user['role']);
    }

    public function testFindByUsernameReturnsNullForNonExistentUser(): void
    {
        $model = new PenggunaModel();
        $user  = $model->findByUsername('nonexistent_user');

        $this->assertNull($user);
    }

    public function testHashPasswordGeneratesSecureBcryptHash(): void
    {
        $model = new PenggunaModel();
        $plain = 'secret123';
        $hash  = $model->hashPassword($plain);

        $this->assertNotSame($plain, $hash);
        $this->assertTrue(password_verify($plain, $hash));
    }

    public function testVerifyPasswordReturnsTrueOnMatchAndFalseOnMismatch(): void
    {
        $model = new PenggunaModel();
        $plain = 'secret123';
        $hash  = $model->hashPassword($plain);
        
        $user = ['password' => $hash];

        $this->assertTrue($model->verifyPassword('secret123', $user));
        $this->assertFalse($model->verifyPassword('wrongpassword', $user));
    }

    public function testValidationFailsForInvalidRole(): void
    {
        $model = new PenggunaModel();
        
        $ok = $model->insert([
            'username'      => 'superman',
            'password'      => 'superman123',
            'nama_pengguna' => 'Clark Kent',
            'role'          => 'super_admin', // role must be admin or petugas
        ]);

        $this->assertFalse($ok);
        $this->assertArrayHasKey('role', $model->errors());
    }

    public function testValidationFailsForShortUsername(): void
    {
        $model = new PenggunaModel();
        
        $ok = $model->insert([
            'username'      => 'us', // min_length[3]
            'password'      => 'password123',
            'nama_pengguna' => 'User',
            'role'          => 'petugas',
        ]);

        $this->assertFalse($ok);
        $this->assertArrayHasKey('username', $model->errors());
    }
}
