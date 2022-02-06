<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('Home/index');
    }

    //To test if email server works mailtrap.io
    public function testEmail()
    { 
        $email = service('email');

        $email->setTo('dwitbooi28@gmail.com');
        
        $email->setSubject('A test email');

        $email->setMessage('<h1>Hello world</h1>');

        if ( $email->send() ) { 
            echo "Message sent";
        }
        else { 
            echo $email->printDebugger();
        }
    }
}
