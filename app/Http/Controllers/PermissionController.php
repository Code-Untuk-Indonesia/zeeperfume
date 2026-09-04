<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $permissions = DB::table('permissions')
            ->select('permissions.*')
            ->selectSub(
                DB::table('role_permission')
                    ->selectRaw('count(*)')
                    ->whereColumn('role_permission.permission_id', 'permissions.id'),
                'roles_count'
            )
            ->orderBy('nama_permission')
            ->paginate($request->integer('per_page', 50));

        return response()->json($permissions);
    }
}
