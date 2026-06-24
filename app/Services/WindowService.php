<?php

namespace App\Services;

use App\Models\KuWindow;
use App\Models\NotificationQueue;
use App\Models\WindowAnalytic;
use App\Models\FomoRiskScore;
use Carbon\Carbon;

class WindowService
{
    public function getActiveWindow(): ?KuWindow
    {
        return KuWindow::where('is_active', true)->first();
    }

    public function getNextWindow(): ?array
    {
        $now = Carbon::now();
        $dayMap = ['Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6,'Sunday'=>0];

        $windows = KuWindow::where('is_active', false)->where('is_recurring', true)->get();

        $best = null;
        $bestSeconds = PHP_INT_MAX;

        foreach ($windows as $window) {
            $targetDay = $dayMap[$window->day_of_week] ?? 0;
            $todayNum  = (int) $now->format('w');

            $daysUntil = ($targetDay - $todayNum + 7) % 7;
            $candidate = $now->copy()->addDays($daysUntil);
            [$h, $m] = explode(':', $window->start_time);
            $candidate->setTime((int)$h, (int)$m, 0);

            if ($candidate->lte($now)) {
                $candidate->addWeek();
            }

            $secondsUntil = $candidate->diffInSeconds($now);
            if ($secondsUntil < $bestSeconds) {
                $bestSeconds = $secondsUntil;
                $best = ['window' => $window, 'seconds_until' => $secondsUntil, 'starts_at' => $candidate];
            }
        }

        return $best;
    }

    public function startWindow(KuWindow $window): WindowAnalytic
    {
        $window->update(['is_active' => true]);

        return WindowAnalytic::create([
            'ku_window_id'     => $window->id,
            'participants'     => $window->estimated_participants,
            'notifications_held' => 0,
            'window_started_at'  => now(),
        ]);
    }

    public function endWindow(KuWindow $window): void
    {
        $analytic = $window->analytics()->whereNull('window_ended_at')->latest()->first();

        $heldCount = NotificationQueue::where('ku_window_id', $window->id)
            ->whereNull('released_at')->count();

        // Release all queued notifications simultaneously
        NotificationQueue::where('ku_window_id', $window->id)
            ->whereNull('released_at')
            ->update(['released_at' => now()]);

        if ($analytic) {
            $analytic->update([
                'notifications_released' => $heldCount,
                'window_ended_at'        => now(),
            ]);
        }

        $window->update(['is_active' => false]);
    }

    public function triggerManualWindow(KuWindow $window): WindowAnalytic
    {
        $window->update(['is_active' => true, 'triggered_manually' => true]);

        return WindowAnalytic::create([
            'ku_window_id'       => $window->id,
            'participants'       => $window->estimated_participants,
            'notifications_held' => 0,
            'window_started_at'  => now(),
        ]);
    }

    public function computeFomoRiskIndex(): FomoRiskScore
    {
        // Simulate computation from LMS data (in production: real API data)
        $notifPressure = rand(300, 450) / 10.0;
        $deadlineClust = rand(280, 420) / 10.0;
        $midnightAct   = rand(310, 460) / 10.0;
        $afterHoursFac = rand(290, 430) / 10.0;

        $composite = round(($notifPressure + $deadlineClust + $midnightAct + $afterHoursFac) / 4, 2);

        $level = match(true) {
            $composite >= 75 => 'critical',
            $composite >= 55 => 'high',
            $composite >= 35 => 'moderate',
            default          => 'low',
        };

        return FomoRiskScore::create([
            'composite_score'       => $composite,
            'notification_pressure' => $notifPressure,
            'deadline_clustering'   => $deadlineClust,
            'midnight_activity'     => $midnightAct,
            'after_hours_faculty'   => $afterHoursFac,
            'risk_level'            => $level,
            'total_users_analyzed'  => rand(40000, 43000),
            'computed_at'           => now(),
        ]);
    }

    public function simulateWindowImpact(string $dayOfWeek, string $startTime, string $endTime): array
    {
        // Simulate AI prediction for a proposed window configuration
        $hourStart = (int) explode(':', $startTime)[0];

        // Lower load windows = higher predicted impact
        $loadScore = match(true) {
            $hourStart >= 20 && $hourStart <= 22 => 85,
            $hourStart >= 14 && $hourStart <= 16 => 72,
            $hourStart >= 7  && $hourStart <= 9  => 61,
            default                               => 55,
        };

        $weekendBonus = in_array($dayOfWeek, ['Saturday', 'Sunday']) ? 8 : 0;
        $predictedImpact = min(98, $loadScore + $weekendBonus + rand(-5, 5));

        return [
            'predicted_cri_improvement'    => round($predictedImpact * 0.35, 1),
            'predicted_notifications_held' => rand(2000, 5000),
            'predicted_participation_rate' => round($predictedImpact * 0.9, 1),
            'fomo_reduction_score'         => $predictedImpact,
            'recommendation'               => $predictedImpact >= 75
                ? 'Highly Recommended — this window aligns with peak connectivity load periods.'
                : 'Moderate Impact — consider shifting by 1–2 hours for optimal results.',
        ];
    }
}
