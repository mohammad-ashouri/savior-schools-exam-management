<?php

namespace App\Livewire;

use App\Models\BankAccount;
use App\Models\Services\Service;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('داشبورد')]
class Dashboard extends Component
{
    public $service_status = [];

    public $expired = 0;
    public $not_expired = 0;

    /**
     * Calculate expired and not expired services
     * @return void
     */
    public function getExpiredAndNotExpiredServices(): void
    {
//        $service_types = Service::where('status', 1)->whereNotNull('expires_at')->distinct('service_type_id')->pluck('service_type_id')->toArray();
//
//        foreach ($service_types as $service_type) {
//            $expired = $not_expired = 0;
//            $services = Service::where('status', 1)->where('service_type_id', $service_type)->get();
//            foreach ($services as $service) {
//                if (checkExpiredService($service->expires_at)) {
//                    $expired++;
//                    $this->expired++;
//                } else {
//                    $not_expired++;
//                    $this->not_expired++;
//                }
//            }
//            $this->service_status[$services[0]->serviceTypeInfo->name] = [
//                'expired' => $expired,
//                'not_expired' => $not_expired,
//            ];
//        }
    }

    /**
     * Render the component
     * @return Factory|Application|View|\Illuminate\View\View
     */
    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        $this->getExpiredAndNotExpiredServices();
        return view('livewire.dashboard', [
        ]);
    }
}
