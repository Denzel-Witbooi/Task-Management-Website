<?php 

namespace App\Libraries;

#region token class
#Object of this class represent 
#A single token value and it's hash
#endregion
class Token 
{ 
    private $token; 

    #allows us to specify the token value
    #Making it optional by assigning the default 
    #value of null
    public function __construct($token = null) 
    {
        #if the argument is null we'll assign a random token as before
        if ($token === null) {
            #when we create a new object of this class
            #this property will be assigned a random token value
            $this->token = bin2hex(random_bytes(16));
        }else { 
            #if not null assign the value of the argument to the property
            $this->token = $token;
        }
    }
    #method will return the value of the token property
    public function getValue()
    {
        return $this->token;
    }

    #method will return the hash of the token value
    public function getHash()
    {
        return hash_hmac('sha256', $this->token, $_ENV['HASH_SECRET_KEY']);
    }
}