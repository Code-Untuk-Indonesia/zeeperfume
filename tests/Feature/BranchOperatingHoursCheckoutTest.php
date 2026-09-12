<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use App\Support\BranchOperatingHours;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BranchOperatingHoursCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_checkout_is_rejected_at_the_branch_closing_time(): void
    {
        $branch = $this->branch('08:00', '22:00');
        $cashier = $this->cashierFor($branch);
        $this->travelTo(Carbon::parse('2026-09-12 22:00:00', config('app.timezone')));

        $this->actingAs($cashier)
            ->postJson(route('kasir.pos.store'), [])
            ->assertForbidden()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Transaksi hanya dapat diproses pada jam operasional outlet (08:00 sampai 22:00).');

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_mobile_checkout_uses_the_same_branch_closing_time(): void
    {
        $branch = $this->branch('08:00', '22:00');
        $cashier = $this->cashierFor($branch);
        $this->travelTo(Carbon::parse('2026-09-12 22:00:00', config('app.timezone')));
        Sanctum::actingAs($cashier);

        $this->postJson('/api/checkout', [])
            ->assertForbidden()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Transaksi hanya dapat diproses pada jam operasional outlet (08:00 sampai 22:00).');

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_checkout_is_still_open_before_the_configured_closing_time(): void
    {
        $branch = $this->branch('08:00', '22:00');
        $cashier = $this->cashierFor($branch);
        $this->travelTo(Carbon::parse('2026-09-12 21:59:59', config('app.timezone')));

        $this->actingAs($cashier)
            ->postJson(route('kasir.pos.store'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cart']);
    }

    public function test_checkout_accepts_opening_time_and_rejects_closing_time_for_overnight_hours(): void
    {
        $branch = $this->branch('22:00', '02:00');
        $hours = app(BranchOperatingHours::class);

        $this->assertNull($hours->closedMessage(
            $branch->id,
            Carbon::parse('2026-09-13 01:59:59', config('app.timezone')),
        ));
        $this->assertNull($hours->closedMessage(
            $branch->id,
            Carbon::parse('2026-09-12 22:00:00', config('app.timezone')),
        ));
        $this->assertSame(
            'Transaksi hanya dapat diproses pada jam operasional outlet (22:00 sampai 02:00).',
            $hours->closedMessage($branch->id, Carbon::parse('2026-09-13 02:00:00', config('app.timezone'))),
        );
    }

    public function test_outlets_without_configured_hours_remain_available(): void
    {
        $branch = $this->branch(null, null);

        $this->assertNull(app(BranchOperatingHours::class)->closedMessage(
            $branch->id,
            Carbon::parse('2026-09-12 23:00:00', config('app.timezone')),
        ));
    }

    public function test_checkout_without_a_branch_is_rejected(): void
    {
        $this->assertSame(
            'Cabang outlet belum ditentukan untuk transaksi ini.',
            app(BranchOperatingHours::class)->closedMessage(
                null,
                Carbon::parse('2026-09-12 23:00:00', config('app.timezone')),
            ),
        );
    }

    private function branch(?string $openingTime, ?string $closingTime): Branch
    {
        return Branch::create([
            'nama_cabang' => 'Outlet Operasional',
            'jam_buka' => $openingTime,
            'jam_tutup' => $closingTime,
        ]);
    }

    private function cashierFor(Branch $branch): User
    {
        $role = Role::create(['nama_role' => 'kasir']);

        return User::factory()->create([
            'role_id' => $role->id,
            'cabang_id' => $branch->id,
        ]);
    }
}
