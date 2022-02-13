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

    public function startPasswordReset()
    {
        #generate a token by creating an object of the Token class
        $token = new Token;

        #in order to able to store these 3 properties to 
        #the database. We need to add them to the allowed fields
        #in the User Model class
        $this->reset_token = $token->getValue(); 
        $this->reset_hash = $token->getHash(); 
        #expiry current date and time + (2hrs-7200s)
        $this->reset_expires_at = date('Y-m-d H:i:s', time() + 7200);
    }
}