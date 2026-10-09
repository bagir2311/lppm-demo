<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Proposal;
use App\Models\PenugasanReview;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create Dosen with required database fields.
     */
    protected function createDosen(array $attributes = []): Dosen
    {
        static $counter = 1;
        $idx = $counter++;
        return Dosen::create(array_merge([
            'nidn' => '040000000' . $idx,
            'nama' => 'Dosen Test ' . $idx,
            'prodi' => 'Teknik Informatika',
            'fakultas' => 'Fakultas Ilmu Komputer',
            'slug' => 'dosen-test-' . $idx . '-' . uniqid(),
            'is_reviewer' => true,
        ], $attributes));
    }

    /**
     * Test routes exist and return correct URLs.
     */
    public function test_reviewer_routes_are_registered(): void
    {
        $this->assertEquals(url('/reviewer'), route('reviewer.index'));
        $this->assertEquals(url('/reviewer/penugasan'), route('reviewer.penugasan.index'));
        $this->assertEquals(url('/reviewer/penugasan'), route('reviewer.penugasan.store'));
        $this->assertEquals(url('/reviewer/penugasan/1'), route('reviewer.penugasan.update', 1));
    }

    /**
     * Test index displays only proposals assigned strictly to the logged-in reviewer.
     */
    public function test_index_shows_proposals_assigned_only_to_logged_in_reviewer(): void
    {
        // Setup two reviewers
        $reviewerA = $this->createDosen([
            'nama' => 'Dr. Budi Santoso',
            'nidn' => '0412345678',
            'prodi' => 'Teknik Informatika',
            'is_reviewer' => true,
        ]);
        $reviewerB = $this->createDosen([
            'nama' => 'Dr. Siti Aminah',
            'nidn' => '0487654321',
            'prodi' => 'Sistem Informasi',
            'is_reviewer' => true,
        ]);

        // Setup proposals
        $proposalA = Proposal::create([
            'judul' => 'Pengembangan Sistem AI LPPM Mahardika',
            'skema' => 'Penelitian Terapan',
            'tahun_anggaran' => 2026,
            'dana_usulan' => 25000000,
            'dosen_id' => $reviewerA->id,
            'status' => 'pending',
        ]);
        $proposalB = Proposal::create([
            'judul' => 'Pemberdayaan UMKM Desa Digital Mahardika',
            'skema' => 'Pengabdian Masyarakat',
            'tahun_anggaran' => 2026,
            'dana_usulan' => 15000000,
            'dosen_id' => $reviewerB->id,
            'status' => 'pending',
        ]);

        // Assign Proposal A to Reviewer A, Proposal B to Reviewer B
        $assignmentA = PenugasanReview::create([
            'proposal_id' => $proposalA->id,
            'reviewer_id' => $reviewerA->id,
            'status_penilaian' => 'pending',
        ]);
        $assignmentB = PenugasanReview::create([
            'proposal_id' => $proposalB->id,
            'reviewer_id' => $reviewerB->id,
            'status_penilaian' => 'pending',
        ]);

        // Simulate Reviewer A is active via session
        $response = $this->withSession(['dosen_id' => $reviewerA->id])->get('/reviewer');

        $response->assertStatus(200);
        $response->assertViewIs('reviewer.index');
        $response->assertSee('Pengembangan Sistem AI LPPM Mahardika');
        $response->assertDontSee('Pemberdayaan UMKM Desa Digital Mahardika');
    }

    /**
     * Test index returns JSON data when requested via API/Accept header.
     */
    public function test_index_returns_json_for_api_requests(): void
    {
        $reviewer = $this->createDosen([
            'nama' => 'Prof. Joko Widodo',
            'is_reviewer' => true,
        ]);

        $proposal = Proposal::create([
            'judul' => 'Riset Komparasi LLM di Institusi Kampus',
            'skema' => 'Penelitian Fundamental',
            'tahun_anggaran' => 2026,
            'dana_usulan' => 30000000,
            'dosen_id' => $reviewer->id,
            'status' => 'pending',
        ]);

        PenugasanReview::create([
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
            'status_penilaian' => 'pending',
        ]);

        $response = $this->withSession(['dosen_id' => $reviewer->id])
                         ->getJson('/reviewer');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'reviewer_id' => $reviewer->id,
            'summary' => [
                'total' => 1,
                'pending' => 1,
            ],
        ]);
    }

    /**
     * Test store method creates a new penugasan review and syncs proposal status.
     */
    public function test_store_creates_penugasan_review_and_updates_proposal_status(): void
    {
        $reviewer = $this->createDosen([
            'nama' => 'Dr. Hendra Gunawan',
            'is_reviewer' => true,
        ]);

        $proposal = Proposal::create([
            'judul' => 'Inovasi Blockchain untuk Ijazah Digital',
            'skema' => 'Penelitian Terapan',
            'tahun_anggaran' => 2026,
            'dana_usulan' => 20000000,
            'dosen_id' => $reviewer->id,
            'status' => 'pending',
        ]);

        $payload = [
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
            'nilai' => 88.5,
            'catatan_revisi' => 'Metodologi sangat jelas dan relevan.',
            'status_penilaian' => 'diterima',
        ];

        $response = $this->withSession(['dosen_id' => $reviewer->id])
                         ->postJson('/reviewer/penugasan', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('status', 'success');

        // Check database
        $this->assertDatabaseHas('penugasan_reviews', [
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
            'nilai' => 88.5,
            'catatan_revisi' => 'Metodologi sangat jelas dan relevan.',
            'status_penilaian' => 'diterima',
        ]);

        // Proposal status synced
        $this->assertDatabaseHas('proposals', [
            'id' => $proposal->id,
            'status' => 'diterima',
        ]);
    }

    /**
     * Test update method successfully stores nilai, catatan_revisi, and updates status in database.
     */
    public function test_update_saves_nilai_catatan_and_changes_status_in_database(): void
    {
        $reviewer = $this->createDosen([
            'nama' => 'Dr. Ratna Sari',
            'is_reviewer' => true,
        ]);

        $proposal = Proposal::create([
            'judul' => 'Deteksi Serangan Jaringan dengan Machine Learning',
            'skema' => 'Penelitian Terapan',
            'tahun_anggaran' => 2026,
            'dana_usulan' => 18000000,
            'dosen_id' => $reviewer->id,
            'status' => 'pending',
        ]);

        $assignment = PenugasanReview::create([
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
            'nilai' => null,
            'catatan_revisi' => null,
            'status_penilaian' => 'pending',
        ]);

        $updateData = [
            'nilai' => 76.5,
            'catatan_revisi' => 'Perbaiki tinjauan pustaka dan rincian alokasi anggaran belanja alat.',
            'status_penilaian' => 'revisi',
        ];

        $response = $this->put(route('reviewer.penugasan.update', $assignment->id), $updateData);

        $response->assertSessionHas('success');

        // Verify penugasan_reviews database record
        $assignment->refresh();
        $this->assertEquals(76.5, $assignment->nilai);
        $this->assertEquals('Perbaiki tinjauan pustaka dan rincian alokasi anggaran belanja alat.', $assignment->catatan_revisi);
        $this->assertEquals('revisi', $assignment->status_penilaian);

        // Verify proposal status synced
        $proposal->refresh();
        $this->assertEquals('revisi', $proposal->status);
    }

    /**
     * Test update rejects invalid nilai outside 0-100.
     */
    public function test_update_rejects_out_of_bounds_nilai(): void
    {
        $reviewer = $this->createDosen();

        $proposal = Proposal::create([
            'judul' => 'Proposal Uji Validasi Skor',
            'tahun_anggaran' => 2026,
            'status' => 'pending',
        ]);

        $assignment = PenugasanReview::create([
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
            'status_penilaian' => 'pending',
        ]);

        $response = $this->put(route('reviewer.penugasan.update', $assignment->id), [
            'nilai' => 150, // Melebihi 100
            'status_penilaian' => 'diterima',
        ]);

        $response->assertSessionHasErrors(['nilai']);
    }

    /**
     * Test update rejects invalid status enum.
     */
    public function test_update_rejects_invalid_status_penilaian(): void
    {
        $reviewer = $this->createDosen();

        $proposal = Proposal::create([
            'judul' => 'Proposal Uji Validasi Status',
            'tahun_anggaran' => 2026,
            'status' => 'pending',
        ]);

        $assignment = PenugasanReview::create([
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
            'status_penilaian' => 'pending',
        ]);

        $response = $this->put(route('reviewer.penugasan.update', $assignment->id), [
            'nilai' => 80,
            'status_penilaian' => 'status_yang_salah',
        ]);

        $response->assertSessionHasErrors(['status_penilaian']);
    }
}
