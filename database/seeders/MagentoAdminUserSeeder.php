<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MagentoAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create bcrypt-hashed password for Magento 2.4.0+
        $password1 = password_hash('password123', PASSWORD_BCRYPT);

        // Create legacy md5:salt password for Magento 2.3.x
        $salt = bin2hex(random_bytes(16));
        $password2 = hash('md5', $salt.'demo123').':'.$salt;

        $adminUsers = [
            [
                'user_id' => 1,
                'username' => 'admin',
                'firstname' => 'Admin',
                'lastname' => 'User',
                'email' => 'admin@magento.test',
                'password' => $password1,
                'is_active' => 1,
                'extra' => null,
                'created' => now(),
                'modified' => now(),
                'logdate' => null,
                'lognum' => 0,
            ],
            [
                'user_id' => 2,
                'username' => 'demo',
                'firstname' => 'Demo',
                'lastname' => 'User',
                'email' => 'demo@magento.test',
                'password' => $password2,
                'is_active' => 1,
                'extra' => null,
                'created' => now(),
                'modified' => now(),
                'logdate' => null,
                'lognum' => 0,
            ],
            [
                'user_id' => 3,
                'username' => 'test',
                'firstname' => 'Test',
                'lastname' => 'Admin',
                'email' => 'test@magento.test',
                'password' => password_hash('testpass123', PASSWORD_BCRYPT),
                'is_active' => 1,
                'extra' => null,
                'created' => now(),
                'modified' => now(),
                'logdate' => null,
                'lognum' => 0,
            ],
        ];

        // Insert into Magento database
        foreach ($adminUsers as $user) {
            DB::connection('magento')
                ->table('admin_user')
                ->insertOrIgnore($user);
        }

        $this->command->info('Magento admin users seeded successfully!');
        $this->command->info('Test credentials:');
        $this->command->info('  - admin@magento.test / password123 (bcrypt)');
        $this->command->info('  - demo@magento.test / demo123 (md5:salt legacy)');
        $this->command->info('  - test@magento.test / testpass123 (bcrypt)');
    }
}
