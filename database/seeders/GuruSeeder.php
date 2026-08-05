<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\User;
use App\Models\Mapel;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $mapelIds = Mapel::pluck('id')->toArray();

        $gurus = [
            [
                'nip'                    => '198501012010011001',
                'nama_lengkap'           => 'Budi Santoso',
                'gelar'                  => 'S.Pd.',
                'jenis_kelamin'          => 'L',
                'tanggal_lahir'          => '1985-01-01',
                'no_hp'                  => '081234567890',
                'email'                  => 'budi.santoso@smpn110.sch.id',
                'mapel_id'               => $mapelIds[0] ?? null,
                'tanggal_mulai_mengajar' => '2010-01-15',
                'tanggal_pensiun'        => '2045-01-01',
            ],
            [
                'nip'                    => '199206142018011002',
                'nama_lengkap'           => 'Hendy Susanto',
                'gelar'                  => 'S.Kom.',
                'jenis_kelamin'          => 'L',
                'tanggal_lahir'          => '1992-06-14',
                'no_hp'                  => '081213063912',
                'email'                  => 'hendy.susanto@smpn110.sch.id',
                'mapel_id'               => $mapelIds[1] ?? null,
                'tanggal_mulai_mengajar' => '2018-02-01',
                'tanggal_pensiun'        => '2052-06-14',
            ],
            [
                'nip'                    => '198807242012022002',
                'nama_lengkap'           => 'Siti Aminah',
                'gelar'                  => 'M.Pd.',
                'jenis_kelamin'          => 'P',
                'tanggal_lahir'          => '1988-07-24',
                'no_hp'                  => '081234567891',
                'email'                  => 'siti.aminah@smpn110.sch.id',
                'mapel_id'               => $mapelIds[2] ?? null,
                'tanggal_mulai_mengajar' => '2012-03-10',
                'tanggal_pensiun'        => '2048-07-24',
            ],
            [
                'nip'                    => '199011152015031003',
                'nama_lengkap'           => 'Ahmad Fauzi',
                'gelar'                  => 'S.Si.',
                'jenis_kelamin'          => 'L',
                'tanggal_lahir'          => '1990-11-15',
                'no_hp'                  => '081234567892',
                'email'                  => 'ahmad.fauzi@smpn110.sch.id',
                'mapel_id'               => $mapelIds[3] ?? null,
                'tanggal_mulai_mengajar' => '2015-04-01',
                'tanggal_pensiun'        => '2050-11-15',
            ],
            [
                'nip'                    => '199404052020032004',
                'nama_lengkap'           => 'Dewi Ratnasari',
                'gelar'                  => 'S.Pd.',
                'jenis_kelamin'          => 'P',
                'tanggal_lahir'          => '1994-04-05',
                'no_hp'                  => '081234567893',
                'email'                  => 'dewi.ratnasari@smpn110.sch.id',
                'mapel_id'               => $mapelIds[4] ?? null,
                'tanggal_mulai_mengajar' => '2020-07-15',
                'tanggal_pensiun'        => '2054-04-05',
            ],
        ];

        foreach ($gurus as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['nama_lengkap'] . ($data['gelar'] ? ', ' . $data['gelar'] : ''),
                    'password' => Hash::make('password123'),
                    'role'     => RoleEnum::GURU ?? 'GURU',
                ]
            );

            Guru::updateOrCreate(
                ['nip' => $data['nip']],
                [
                    'user_id'                => $user->id,
                    'nama_lengkap'           => $data['nama_lengkap'],
                    'gelar'                  => $data['gelar'],
                    'jenis_kelamin'          => $data['jenis_kelamin'],
                    'tanggal_lahir'          => $data['tanggal_lahir'],
                    'no_hp'                  => $data['no_hp'],
                    'mapel_id'               => $data['mapel_id'],
                    'tanggal_mulai_mengajar' => $data['tanggal_mulai_mengajar'],
                    'tanggal_pensiun'        => $data['tanggal_pensiun'],
                ]
            );
        }
    }
}
