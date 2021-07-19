<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$UnLIBQB1uZZC1r5msFWTPuZCZsMBUpZINpJ48G5FmMxz6yVGP83rO',
                'remember_token' => null,
            ],
            [
                'name'           => 'Agent 1',
                'email'          => 'agent1@agent1.com',
                'password'       => '$2y$10$UnLIBQB1uZZC1r5msFWTPuZCZsMBUpZINpJ48G5FmMxz6yVGP83rO',
                'remember_token' => null,
            ],
            [
                'name'           => 'Agent 2',
                'email'          => 'agent2@agent2.com',
                'password'       => '$2y$10$UnLIBQB1uZZC1r5msFWTPuZCZsMBUpZINpJ48G5FmMxz6yVGP83rO',
                'remember_token' => null,
            ],
            [
                'name'           => 'Agent 3',
                'email'          => 'agent3@agent3.com',
                'password'       => '$2y$10$UnLIBQB1uZZC1r5msFWTPuZCZsMBUpZINpJ48G5FmMxz6yVGP83rO',
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
