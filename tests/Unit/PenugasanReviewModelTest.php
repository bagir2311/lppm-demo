<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PenugasanReview;
use App\Models\Proposal;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PenugasanReviewModelTest extends TestCase
{
    /**
     * Test PenugasanReview configuration and fillable fields.
     */
    public function test_penugasan_review_fillable_and_casts(): void
    {
        $review = new PenugasanReview();

        $expectedFillable = [
            'proposal_id',
            'reviewer_id',
            'nilai',
            'catatan_revisi',
            'status_penilaian',
        ];

        $this->assertEquals($expectedFillable, $review->getFillable());
        $this->assertEquals('float', $review->getCasts()['nilai']);
    }

    /**
     * Test PenugasanReview relationships to Proposal, Dosen, and User.
     */
    public function test_penugasan_review_relationships(): void
    {
        $review = new PenugasanReview();

        $this->assertInstanceOf(BelongsTo::class, $review->proposal());
        $this->assertInstanceOf(Proposal::class, $review->proposal()->getRelated());

        $this->assertInstanceOf(BelongsTo::class, $review->reviewer());
        $this->assertInstanceOf(Dosen::class, $review->reviewer()->getRelated());

        $this->assertInstanceOf(BelongsTo::class, $review->dosen());
        $this->assertInstanceOf(Dosen::class, $review->dosen()->getRelated());

        $this->assertInstanceOf(BelongsTo::class, $review->user());
        $this->assertInstanceOf(User::class, $review->user()->getRelated());
    }

    /**
     * Test Proposal relationships to Dosen and PenugasanReview.
     */
    public function test_proposal_relationships(): void
    {
        $proposal = new Proposal();

        $expectedFillable = [
            'judul',
            'dosen_id',
            'skema',
            'tahun',
            'abstrak',
            'file_proposal',
            'status',
        ];

        $this->assertEquals($expectedFillable, $proposal->getFillable());

        $this->assertInstanceOf(BelongsTo::class, $proposal->dosen());
        $this->assertInstanceOf(Dosen::class, $proposal->dosen()->getRelated());

        $this->assertInstanceOf(HasMany::class, $proposal->penugasanReviews());
        $this->assertInstanceOf(PenugasanReview::class, $proposal->penugasanReviews()->getRelated());

        $this->assertInstanceOf(HasOne::class, $proposal->penugasanReview());
        $this->assertInstanceOf(PenugasanReview::class, $proposal->penugasanReview()->getRelated());
    }

    /**
     * Test Dosen and User relationships to PenugasanReview.
     */
    public function test_dosen_and_user_relations_to_penugasan_reviews(): void
    {
        $dosen = new Dosen();
        $this->assertInstanceOf(HasMany::class, $dosen->proposals());
        $this->assertInstanceOf(Proposal::class, $dosen->proposals()->getRelated());
        $this->assertInstanceOf(HasMany::class, $dosen->penugasanReviews());
        $this->assertInstanceOf(PenugasanReview::class, $dosen->penugasanReviews()->getRelated());

        $user = new User();
        $this->assertInstanceOf(HasMany::class, $user->penugasanReviews());
        $this->assertInstanceOf(PenugasanReview::class, $user->penugasanReviews()->getRelated());
    }

    /**
     * Test migration files existence.
     */
    public function test_migration_files_exist(): void
    {
        $this->assertFileExists(database_path('migrations/2026_10_09_000002_create_proposals_table.php'));
        $this->assertFileExists(database_path('migrations/2026_10_09_000003_create_penugasan_reviews_table.php'));
    }
}
