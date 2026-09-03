<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $permissions = Permission::query()
            ->withCount('roles')
            ->orderBy('nama_permission')
            ->paginate($request->integer('per_page', 50));

        return response()->json($permissions);
    }
}
