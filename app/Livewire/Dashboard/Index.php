<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.demo1.base')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
