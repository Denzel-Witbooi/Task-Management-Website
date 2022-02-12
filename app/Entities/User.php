<?php 

namespace App\Entities;

class User extends \CodeIgniter\Entity\Entity
{ 
    public function verifyPassword($password)
    { 
        #this the method we would to call on the user object 
        #hence placing it on the User entity class 
        
        return password_verify($password, $this->password_hash);
    }

    public function startActivation()
    { 
        $this->token = bin2Hex(random_bytes(16));

        //assigning hash property to the current user object
        $this->activation_hash = hash_hmac('sha256', $this->token, $_ENV['HASH_SECRET_KEY']);
        
    }

    public function activate()
    { 
        $this->is_active = true; 
        //set to null as we no longer need it
        $this->activation_hash = null;
    }
}