<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BranchOperatingHours
{
    public function closedMessage(int|string|null $branchId, ?Carbon $now = null): ?string
    {
        if ($branchId === null) {
            return 'Cabang outlet belum ditentukan untuk transaksi ini.';
        }

        $branch = DB::table('branches')
            ->where('id', $branchId)
            ->whereNull('deleted_at')
            ->first(['jam_buka', 'jam_tutup']);

        if ($branch === null) {
            return 'Outlet transaksi tidak ditemukan.';
        }

        if ($branch->jam_buka === null || $branch->jam_tutup === null) {
            return null;
        }

        $openingTime = $this->normalizeTime($branch->jam_buka);
        $closingTime = $this->normalizeTime($branch->jam_tutup);
        $currentTime = ($now ?? now())->format('H:i:s');
        $isOpenAllDay = $openingTime === $closingTime;

        $isWithinOperatingHours = $isOpenAllDay || ($openingTime < $closingTime
            ? $currentTime >= $openingTime && $currentTime < $closingTime
            : $currentTime >= $openingTime || $currentTime < $closingTime);

        if ($isWithinOperatingHours) {
            return null;
        }

        return sprintf(
            'Transaksi hanya dapat diproses pada jam operasional outlet (%s sampai %s).',
            substr($openingTime, 0, 5),
            substr($closingTime, 0, 5),
        );
    }

    private function normalizeTime(string $time): string
    {
        $parts = array_pad(explode(':', $time), 3, '00');

        return sprintf('%02d:%02d:%02d', (int) $parts[0], (int) $parts[1], (int) $parts[2]);
    }
}
