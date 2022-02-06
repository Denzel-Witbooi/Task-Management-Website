<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $model = new \App\Models\UserModel;

        $data = [ 
            'name'      => 'Admin', 
            'email'     => 'admin@example.com', 
            'password'  => 'secret', 
            'is_admin'  => true,
            'is_active' => true
        ];
        //temporarily disable validation to prevent errors from the password confirmation
        //field
        //As the code in the seeder class can be trusted 
        //we can turn temp turn the protect off for the allowedfields
        //And insert the is_admin field
        $model->skipValidation(true) 
              ->protect(false)
              ->insert($data);
    }
}
