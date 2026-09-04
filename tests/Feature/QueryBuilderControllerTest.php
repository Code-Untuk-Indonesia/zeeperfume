<?php

namespace Tests\Feature;

use App\Http\Controllers\BranchController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class QueryBuilderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/_test/query/branches', [BranchController::class, 'index']);
        Route::post('/_test/query/branches', [BranchController::class, 'store']);
        Route::put('/_test/query/branches/{branch}', [BranchController::class, 'update'])
            ->middleware(SubstituteBindings::class);
        Route::put('/_test/query/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])
            ->middleware(SubstituteBindings::class);
        Route::post('/_test/query/users', [UserController::class, 'store']);
        Route::get('/_test/query/users/{user}', [UserController::class, 'show'])
            ->middleware(SubstituteBindings::class);
    }

    public function test_branch_crud_uses_query_builder_and_preserves_counts(): void
    {
        $created = $this->postJson('/_test/query/branches', [
            'nama_cabang' => 'Cabang Selatan',
            'alamat' => 'Jl. Selatan',
        ])->assertCreated();

        $branchId = $created->json('id');

        $this->putJson('/_test/query/branches/'.$branchId, [
            'nama_cabang' => 'Cabang Selatan Baru',
            'alamat' => 'Jl. Selatan',
        ])->assertOk()->assertJsonPath('nama_cabang', 'Cabang Selatan Baru');

        $this->getJson('/_test/query/branches')
            ->assertOk()
            ->assertJsonPath('data.0.id', $branchId)
            ->assertJsonPath('data.0.users_count', 0)
            ->assertJsonPath('data.0.transactions_count', 0);
    }

    public function test_user_and_role_queries_preserve_nested_relations_and_hash_passwords(): void
    {
        $branchId = $this->postJson('/_test/query/branches', [
            'nama_cabang' => 'Cabang Tengah',
        ])->assertCreated()->json('id');
        $role = Role::create(['nama_role' => 'Kasir']);
        $permission = Permission::create([
            'nama_permission' => 'transactions.create',
            'deskripsi' => 'Membuat transaksi',
        ]);

        $this->putJson('/_test/query/roles/'.$role->getKey().'/permissions', [
            'permission_ids' => [$permission->getKey()],
        ])->assertOk()->assertJsonPath('permissions.0.id', $permission->getKey());

        $created = $this->postJson('/_test/query/users', [
            'role_id' => $role->getKey(),
            'nama_lengkap' => 'Kasir Query',
            'username' => 'kasir.query',
            'password' => 'password123',
            'cabang_id' => $branchId,
        ])->assertCreated()
            ->assertJsonMissingPath('password')
            ->assertJsonPath('role.id', $role->getKey())
            ->assertJsonPath('branch.id', $branchId);

        $userId = $created->json('id');
        $password = DB::table('users')
            ->where('id', $userId)
            ->value('password');

        $this->assertTrue(Hash::check('password123', $password));
        $this->getJson('/_test/query/users/'.$userId)
            ->assertOk()
            ->assertJsonPath('role.permissions.0.id', $permission->getKey());
    }
}
