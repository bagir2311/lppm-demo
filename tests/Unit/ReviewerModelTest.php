<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Dosen;
use App\Models\User;

class ReviewerModelTest extends TestCase
{
    /**
     * Test that Dosen model has is_reviewer in fillable and boolean cast.
     */
    public function test_dosen_model_has_is_reviewer_configuration(): void
    {
        $dosen = new Dosen();
        $this->assertContains('is_reviewer', $dosen->getFillable());
        $this->assertEquals('boolean', $dosen->getCasts()['is_reviewer']);
    }

    /**
     * Test that User model has is_reviewer in fillable and boolean cast.
     */
    public function test_user_model_has_is_reviewer_configuration(): void
    {
        $user = new User();
        $this->assertContains('is_reviewer', $user->getFillable());
        $this->assertEquals('boolean', $user->getCasts()['is_reviewer']);
    }

    /**
     * Test that migration file exists.
     */
    public function test_migration_file_exists(): void
    {
        $this->assertFileExists(database_path('migrations/2026_10_09_000001_add_is_reviewer_to_dosens_and_users_table.php'));
    }
}
