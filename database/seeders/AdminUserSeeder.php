<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{User, config, Marque, Service, Category};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;

use App\Mail\register as MailRegister;


class AdminUserSeeder extends Seeder
{




    public function run(): void
    {

        // 1. Récupérer ou créer le rôle admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 2. Récupérer TOUTES les permissions déjà créées dans DatabaseSeeder
        $allPermissions = Permission::all();

        // 3. Synchroniser les permissions avec le rôle (au cas où)
        $adminRole->syncPermissions($allPermissions);

        // 4. Créer le nouvel utilisateur Admin
   


        $user1 = User::updateOrCreate(
            ['email' => 'tuemothomas@gmail.com'], // On vérifie par l'email
            [
                'nom' => 'Tuemo',
                'prenom' => 'Thomas',
                'role' => 'admin',
                'adresse' => 'Douala, Cameroun',
                'phone' => '672959424',
                'code_postal' => '00237',
                'password' => Hash::make('672959424'),

            ]

        );


        $user2 = User::updateOrCreate(
            ['email' => 'thomastuemo@gmail.com'], // On vérifie par l'email
            [
                'nom' => 'Tuemo',
                'prenom' => 'Thomas',
                'role' => 'admin',
                'adresse' => 'Douala, Cameroun',
                'phone' => '672958053',
                'code_postal' => '00237',
                'password' => Hash::make('672958053'),
            ]
        );


    
        // 5. Lui assigner le rôle
        $user1->assignRole($adminRole);
        $user2->assignRole($adminRole);
     
    //    Mail::to($user1->email)->send(new  MailRegister($user1));
      //  Mail::to($user2->email)->send(new  MailRegister($user2));

       
        $this->command->info("L'utilisateur {$user1->email} a été créé et possède toutes les permissions !");
        $this->command->info("L'utilisateur {$user2->email} a été créé et possède toutes les permissions !");
    }

    //////////////Executer    la commande   php artisan db:seed --class=AdminUserSeeder
}
