<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingHeaderAuthTest extends TestCase
{
    /**
     * Test that guest users cannot see laporan, reviewer, hibah, and surat in the landing page header.
     */
    public function test_guest_cannot_see_restricted_nav_items_in_landing_header(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Should not see the links in navigation
        $response->assertDontSee(route('hibah'));
        $response->assertDontSee(route('surat.index'));
        $response->assertDontSee(route('berkas.index'));
        $response->assertDontSee(route('reviewer.dashboard'));

        // Should see public navigation items
        $response->assertSee('Beranda');
        $response->assertSee('Katalog');
        $response->assertSee('Profil');
        $response->assertSee('Pengumuman');
        $response->assertSee('Dosen');
        $response->assertSee('Peraturan');
        $response->assertSee('Jurnal');
        $response->assertSee('Kontak');
    }

    /**
     * Test that logged in user can see laporan, reviewer, hibah, and surat in the landing page header.
     */
    public function test_logged_in_user_can_see_restricted_nav_items_in_landing_header(): void
    {
        $response = $this->withSession(['user_logged_in' => true])->get('/');

        $response->assertStatus(200);

        // Should see the links in navigation
        $response->assertSee(route('hibah'));
        $response->assertSee(route('surat.index'));
        $response->assertSee(route('berkas.index'));
        $response->assertSee(route('reviewer.dashboard'));
    }

    /**
     * Test that logged in admin can see restricted nav items and dashboard button.
     */
    public function test_logged_in_admin_can_see_restricted_nav_items_and_dashboard_admin(): void
    {
        $response = $this->withSession(['admin_logged_in' => true])->get('/');

        $response->assertStatus(200);

        // Should see the links in navigation
        $response->assertSee(route('hibah'));
        $response->assertSee(route('surat.index'));
        $response->assertSee(route('berkas.index'));
        $response->assertSee(route('reviewer.dashboard'));
        $response->assertSee('Dashboard Admin');
    }
}
