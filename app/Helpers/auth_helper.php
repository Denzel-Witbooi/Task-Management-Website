<?php 

#helper is a collection of functions
#Not a class, so we don't need a namespace
#Or a class declaration 

#good practice to check if function already exists
#to prevent overriting

if (! function_exists('current_user')) {
    
    function current_user()
    { 
        #created an instance of the Authentication class
        // $auth = new \App\Libraries\Authentication; 
        $auth = service('auth'); 

       return $auth->getCurrentuser();
    }
}


