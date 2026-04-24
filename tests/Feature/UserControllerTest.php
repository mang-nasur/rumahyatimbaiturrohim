<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_users_index()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    /** @test */
    public function non_admin_cannot_view_users_index()
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->get(route('users.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_create_user_form()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    /** @test */
    public function admin_can_create_user()
    {
        $admin = User::factory()->admin()->create();

        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $data);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User berhasil ditambahkan');
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => 'staff',
        ]);
    }

    /** @test */
    public function admin_can_view_edit_user_form()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
    }

    /** @test */
    public function admin_can_update_user()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'role' => 'staff',
        ]);

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'bendahara',
        ];

        $response = $this->actingAs($admin)->put(route('users.update', $user), $data);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User berhasil diperbarui');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'bendahara',
        ]);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User berhasil dihapus');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function non_admin_cannot_create_user()
    {
        $staff = User::factory()->staff()->create();

        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
        ];

        $response = $this->actingAs($staff)->post(route('users.store'), $data);

        $response->assertStatus(403);
    }

    /** @test */
    public function non_admin_cannot_update_user()
    {
        $staff = User::factory()->staff()->create();
        $user = User::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'bendahara',
        ];

        $response = $this->actingAs($staff)->put(route('users.update', $user), $data);

        $response->assertStatus(403);
    }

    /** @test */
    public function non_admin_cannot_delete_user()
    {
        $staff = User::factory()->staff()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($staff)->delete(route('users.destroy', $user));

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_user_management()
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function admin_can_view_reset_password_form()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('users.reset-password', $user));

        $response->assertStatus(200);
        $response->assertViewIs('users.reset-password');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function admin_can_reset_user_password()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $data = [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($admin)->post(route('users.reset-password.update', $user), $data);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'Password berhasil direset');
        
        // Verify user can login with new password
        $this->assertTrue(auth()->attempt([
            'email' => $user->email,
            'password' => 'newpassword123',
        ]));
    }

    /** @test */
    public function password_reset_validates_minimum_length()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $data = [
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->actingAs($admin)->post(route('users.reset-password.update', $user), $data);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_reset_validates_confirmation()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $data = [
            'password' => 'newpassword123',
            'password_confirmation' => 'different123',
        ];

        $response = $this->actingAs($admin)->post(route('users.reset-password.update', $user), $data);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function non_admin_cannot_view_reset_password_form()
    {
        $staff = User::factory()->staff()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($staff)->get(route('users.reset-password', $user));

        $response->assertStatus(403);
    }

    /** @test */
    public function non_admin_cannot_reset_password()
    {
        $staff = User::factory()->staff()->create();
        $user = User::factory()->create();

        $data = [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($staff)->post(route('users.reset-password.update', $user), $data);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_reset_password()
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.reset-password', $user));

        $response->assertRedirect(route('login'));
    }
}
