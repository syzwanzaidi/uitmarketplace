<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Demo accounts. Every password is "password".
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['name' => 'Admin', 'email' => 'admin@uitmarketplace.test', 'membership_type' => User::TYPE_ADMIN],
            ['name' => 'Aisyah Rahman', 'email' => 'aisyah@student.uitm.edu.my', 'membership_type' => User::TYPE_UITM, 'phone' => '0123456789', 'city' => 'Shah Alam', 'state' => 'Selangor'],
            ['name' => 'Hakim Zulkifli', 'email' => 'hakim@student.uitm.edu.my', 'membership_type' => User::TYPE_UITM, 'phone' => '0139876543', 'city' => 'Shah Alam', 'state' => 'Selangor'],
            ['name' => 'Daniel Lim', 'email' => 'buyer@example.com', 'membership_type' => User::TYPE_PUBLIC, 'phone' => '0171112233',
                'address1' => '12, Jalan Plumbum 7/102', 'postcode' => '40000', 'city' => 'Shah Alam', 'state' => 'Selangor', 'country' => 'Malaysia'],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(['email' => $account['email']], [
                ...$account,
                'password' => 'password',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }
    }
}
