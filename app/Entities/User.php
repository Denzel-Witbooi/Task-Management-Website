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

        //replaced with the getValue method from the token object
        // $this->token = bin2Hex(random_bytes(16));

        $this->token = $token->getValue();



        //assigning hash property to the current user object
        //replaced with the getHash method from the token object
        // $this->activation_hash = hash_hmac('sha256', $this->token, $_ENV['HASH_SECRET_KEY']);
        $this->token = $token->getHash();
    }

    public function activate()
    { 
        $this->is_active = true; 
        //set to null as we no longer need it
        $this->activation_hash = null;
    }

    public function startPasswordReset()
    {
        //generate a random token by creating an object
        //of the token class
        $token = new Token; 

        $this->reset_token = $token->getValue(); 

        $this->reset_hash = $token->getHash();

        //get current date and time plus 2hours(7200 seconds)
        $this->reset_expires_at = date('Y-m-d H:i:s', time() + 7200);
    }



}