<?php

namespace Database\Seeders;

use App\Models\StaffSchedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ProductSeeder::class);

        User::updateOrCreate(
            ['email' => 'admin@sambalbakar.test'],
            ['name' => 'Admin Sambal Bakar', 'password' => 'password', 'role' => User::ROLE_ADMIN]
        );

        User::updateOrCreate(
            ['email' => 'kasir@sambalbakar.test'],
            ['name' => 'Kasir Sambal Bakar', 'password' => 'password', 'role' => User::ROLE_KASIR]
        );

        User::updateOrCreate(
            ['email' => 'siti@sambalbakar.test'],
            ['name' => 'Siti Rahma', 'password' => 'password', 'role' => User::ROLE_KASIR]
        );

        User::updateOrCreate(
            ['email' => 'budi@sambalbakar.test'],
            ['name' => 'Budi Santoso', 'password' => 'password', 'role' => User::ROLE_KASIR]
        );

        $this->seedStaffSchedules();
    }

    private function seedStaffSchedules(): void
    {
        $staff = User::query()
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_KASIR])
            ->orderBy('name')
            ->get();

        $shifts = ['Pagi', 'Siang', 'Malam'];

        foreach (range(0, 6) as $offset) {
            $date = now()->startOfDay()->addDays($offset)->toDateString();

            foreach ($staff as $index => $member) {
                StaffSchedule::firstOrCreate(
                    [
                        'user_id' => $member->id,
                        'duty_date' => $date,
                        'shift' => $shifts[$index % count($shifts)],
                    ],
                    ['note' => $offset === 0 ? 'Bertugas hari ini' : null]
                );
            }
        }
    }
}
