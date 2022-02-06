<?php  
 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface; 
use CodeIgniter\HTTP\ResponseInterface; 
use CodeIgniter\Filters\FilterInterface;

class LoginFilter implements FilterInterface
{ 
    public function before(RequestInterface $request, $arguments = null)
    { 
         //"!" negates the return value of this call
         if (! service('auth')->isLoggedIn()) {
            
            session()->set('redirect_url', current_url());

            return redirect()->to('/login')
                             ->with('info', 'Please login first');
        }

    }

    public function after(RequestInterface $request, ResponseInterface $responseInterface, $arguments = null)
    { 

    }
}