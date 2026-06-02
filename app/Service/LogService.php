<?php

namespace App\Service;

use App\Models\ActivityLog;
use Jenssegers\Agent\Agent;

class LogService
{
    public static function log(string $activity_type, array $activity, $user_id = null, $appliance_id = null): void
    {
        $agent = new Agent();

        try {
            ActivityLog::create([
                'user_id' => $user_id ?? auth()->user()->id,
                'appliance_id' => $appliance_id,
                'activity_type' => $activity_type,
                'activity' => json_encode($activity, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'ip_address' => request()->ip(),
                'device' => $agent->device(),
                'platform' => $agent->platform(),
                'platform_version' => $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'device_type' => $agent->deviceType(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Activity log failed: ' . $e->getMessage());
        }
    }
}
