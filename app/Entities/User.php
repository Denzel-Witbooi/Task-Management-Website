<?php 

namespace App\Entities;

use App\Libraries\Token;

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
        $token = new Token; 

        $this->token = $token->getValue();

        //assigning hash property to the current user object
        $this->activation_hash = $token->getHash();
    }

    public function activate()
    { 
        $this->is_active = true; 
        //set to null as we no longer need it
        $this->activation_hash = null;
    }
}