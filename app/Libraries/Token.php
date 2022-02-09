<?php 

namespace App\Libraries; 

//Objects of this class represent a 
// single token value and it's hash
class Token 
{ 
    private $token; 

    public function __construct($token = null) {
       //Having a condition will ensure that 
       //the code we have in the UserEntity class 
       //will work as before without the condition
        if ($token === null) {
            $this->token = bin2hex(random_bytes(16)) ;
        }else{ 
            //we'll assign the value of the argument
            //to the property
            $this->token = $token;
        }
    }

    /* Method to return the token value*/
    public function getValue()
    {
        return $this->token;
    }

    /* Method to return the hash of the token value */
    public function getHash()
    {
        return hash_hmac('sha256', $this->token, $_ENV['HASH_SECRET_KEY']);
    }
}