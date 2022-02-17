<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Yeni kullanıcı kaydı testi
     *
     * @return void
     */
    public function test_yeni_kullanici_kayit()
    {
        $this->postJson(route('auth.register'), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'secret',
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'name',
                    'email',
                    'token',
                    'token_type',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);
    }

    /**
     * Kullanıcı girişi testi
     *
     * @return void
     */
    public function test_kullanici_girisi()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret'),
        ]);

        $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'secret',
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'name',
                    'email',
                    'token',
                    'token_type',
                ],
            ]);
    }

    /**
     * Kullanıcı e-posta adresi doğrulama testi
     *
     * @return void
     */
    public function test_eposta_adresi_dogrulama()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret'),
            'email_verification_code' => 1000,
        ]);

        $this->post(route('auth.emailVerify'), [
            'email' => $user->email,
            'email_verification_code' => 1000,
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'name',
                    'email',
                    'token',
                    'token_type',
                ],
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
            'email_verified_at' => null,
        ]);
    }
}
