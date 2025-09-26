<?php

namespace App\Excel;

use Illuminate\Contracts\View\View;

class PayRegFinanceTemplate
{

    protected $data;
    protected $label;

    public function view() : View
    {
        return view('',[
            'data' => $this->data,
            'label' => $this->label
        ]);
    }
}
