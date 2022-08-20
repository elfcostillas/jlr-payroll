<?php

namespace App\Libraries;

use Illuminate\Contracts\Validation\Rule;

class DefaultValue implements Rule
{
    public function passes($attribute, $value)
    {
        return $value > 10;
    }

    public function message()
    {
        return ':attribute needs more cowbell!';
    }
}



?>