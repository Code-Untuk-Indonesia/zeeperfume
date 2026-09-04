<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::withTrashed()->updateOrCreate(
            ['nama_cabang' => 'Outlet Pusat'],
            ['alamat' => 'Alamat outlet belum diatur'],
        );

        if ($branch->trashed()) {
            $branch->restore();
        }
    }
}
