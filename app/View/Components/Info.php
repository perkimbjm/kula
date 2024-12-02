<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Info extends Component
{
    public $information;  // Harus public

    public function __construct($information)
    {
        $this->information = $information;
    }

    public function render()
    {
        // Pass variabel ke view component
        return view('components.info', [
            'information' => $this->information
        ]);
    }
}