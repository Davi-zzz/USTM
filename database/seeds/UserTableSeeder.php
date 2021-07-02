<?php

use App\Person;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $person = Person::create([
            'name' => 'Admin',
            'email' => 'Admin@admin.com',
            'nickname' => 'Admin',
            'nif' => '000.000.000-01',
            'phone' => '(99) 9999-9999',
            'address' => 'Quadra 604 Sul, Al 04, lt 99999',
            'zip_code' => '77010-001',
            'city_id' => '443'
        ]);

        $user = User::create([
            'person_id' => $person->id,
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'profile_type' => 4
        ]);

        $user->assignRole('super_administrador');

        // Exibe uma informação no console durante o processo de seed
        $this->command->info('User '.$user->name.' created');

    }
}
