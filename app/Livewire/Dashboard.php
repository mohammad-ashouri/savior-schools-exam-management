<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('داشبورد')]
class Dashboard extends Component
{
    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        return view('livewire.dashboard', [
        ]);
    }
}
