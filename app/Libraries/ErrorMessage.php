<?php
namespace App\Libraries;

class ErrorMessage
{
    public function friendlyMsg($errCode, $action, $e = null)
    {        
        // dd($errCode); die;
        switch($errCode)
        {
            case '23302':
                return "One of the fields seems to be empty. Please try again.";
            break;
            default:
                return empty($this->exceptions[$action]) ?
                    'Please call for IT for support. Details: ' . (empty($e) ?
                        '' : ' ' . $e->getMessage()) : $this->exceptions[$action];
            break;
        }
    }
}