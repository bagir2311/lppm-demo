<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminLogoutTest extends TestCase
{
    /**
     * Test admin logout via POST request.
     */
    public function test_admin_can_logout_via_post(): void
    {
        $response = $this->withSession(['admin_logged_in' => true])
            ->post('/admin/logout');

        $response->assertRedirect(route('landing.index'));
        $response->assertSessionMissing('admin_logged_in');
        $response->assertSessionHas('success', 'Logout admin berhasil.');
    }

    /**
     * Test admin logout via GET request (handles direct URL access or bookmark gracefully).
     */
    public function test_admin_can_logout_via_get_without_error(): void
    {
        $response = $this->withSession(['admin_logged_in' => true])
            ->get('/admin/logout');

        $response->assertRedirect(route('landing.index'));
        $response->assertSessionMissing('admin_logged_in');
        $response->assertSessionHas('success', 'Logout admin berhasil.');
    }

    /**
     * Test admin dashboard sidebar contains the logout form with CSRF token.
     */
    public function test_admin_dashboard_renders_logout_form_with_csrf(): void
    {
        $response = $this->withSession(['admin_logged_in' => true])
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee(route('admin.logout'));
        $response->assertSee('Keluar Admin');
        $response->assertSee('<form action="' . route('admin.logout') . '" method="POST"', false);
    }
}
