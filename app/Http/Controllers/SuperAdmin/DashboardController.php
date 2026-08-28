<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Traits\BelongsToSekolah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(): View
    {
        $adminQuery = User::withoutGlobalScope(BelongsToSekolah::class); // FIX INI

        $totalAdmin = (clone $adminQuery)->where('role', RoleEnum::ADMIN_SEKOLAH->value)->count();
        $totalUser = (clone $adminQuery)->whereNotIn('role', [RoleEnum::SUPER_ADMIN->value, RoleEnum::ADMIN_SEKOLAH->value])->count();
        $latestUsers = (clone $adminQuery)->latest()->take(5)->get();

        $dbStatus = true;
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = false;
        }
        $storageStatus = true;
        try {
            Storage::disk('local')->exists('.');
        } catch (\Exception $e) {
            $storageStatus = false;
        }
        $serverStatus = ($dbStatus && $storageStatus) ? 'Normal' : 'Gangguan';

        [$totalGB, $usedGB, $storagePercent] = $this->getStorageInfo();

        return view('superadmin.super_admin', compact(
            'totalAdmin',
            'totalUser',
            'latestUsers',
            'serverStatus',
            'dbStatus',
            'storageStatus',
            'totalGB',
            'usedGB',
            'storagePercent'
        ));
    }

    public function backupDatabase(): StreamedResponse
    {
        $dbName = config('database.connections.mysql.database');
        $filename = 'backup-database-' . now()->format('Y-m-d-His') . '.sql';

        return response()->streamDownload(function () use ($dbName) {
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $dbName;
            $pdo = DB::getPdo(); // ambil pdo 1x

            echo "-- SIP SMP Database Backup\n";
            echo "-- Waktu Backup: " . now()->toDateTimeString() . "\n\n";
            echo "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                echo "-- Table: {$tableName}\n";
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0]->{'Create Table'};
                echo "DROP TABLE IF EXISTS `{$tableName}`;\n";
                echo $createTable . ";\n\n";

                DB::table($tableName)->orderBy('id')->chunk(1000, function ($rows) use ($tableName, $pdo) {
                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $keys = implode('`, `', array_keys($rowArray));
                        $values = implode(', ', array_map(fn($val) => is_null($val) ? "NULL" : $pdo->quote($val), $rowArray)); // FIX INI
                        echo "INSERT INTO `{$tableName}` (`{$keys}`) VALUES ({$values});\n";
                    }
                });
                echo "\n";
            }
            echo "SET FOREIGN_KEY_CHECKS=1;\n";
        }, $filename, ['Content-Type' => 'application/sql']);
    }

    private function getStorageInfo(): array
    {
        $storagePath = storage_path();
        $totalSpace = disk_total_space($storagePath) ?: 1;
        $freeSpace = disk_free_space($storagePath) ?: 0;
        $usedSpace = $totalSpace - $freeSpace;
        return [
            round($totalSpace / 1024 ** 3, 2),
            round($usedSpace / 1024 ** 3, 2),
            round(($usedSpace / $totalSpace) * 100, 1)
        ];
    }
}
