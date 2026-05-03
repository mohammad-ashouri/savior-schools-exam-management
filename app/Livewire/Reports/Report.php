<?php

namespace App\Livewire\Reports;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class Report extends Component
{
    #[Url]
    public $report_type = null;

    public function mount()
    {
//        if ($this->appliance_id) {
//            $this->student_appliance_status = StudentApplianceStatus::findOrFail($this->appliance_id);
//        }
    }

    /**
     * Render the component
     * @return View|Application|Factory|\Illuminate\View\View
     */
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        return view('livewire.reports.report');
    }
}
