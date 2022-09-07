<?php
namespace App\Libraries;

class ErrorMessage
{
    public function friendlyMsg($errCode, $action, $e = null)
    {        
        
        switch($errCode)
        {
            case '23302':
                return "One of the fields seems to be empty. Please try again.";
            break;
            case '23000' : case 23000:
                return "Duplicate.";
            break;
            default:
                return empty($this->exceptions[$action]) ?
                    'Please call for IT for support. Details: ' . (empty($e) ?
                        '' : ' ' . $e->getMessage()) : $this->exceptions[$action];
            break;
        }
    }
}