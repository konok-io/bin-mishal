<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            BranchSeeder::class,
            SettingSeeder::class,
            CmsSeeder::class,
            VisaTypeSeeder::class,
            AirlineSeeder::class,
            AirportSeeder::class,
            InvestorServiceSeeder::class,
            ContentSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
