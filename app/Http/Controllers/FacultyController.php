<?php

namespace App\Http\Controllers;

use App\Models\FacultyShield;
use App\Models\NotificationQueue;
use App\Models\KuWindow;
use App\Services\WindowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyController extends Controller
{
    protected WindowService $windowService;

    public function __construct(WindowService $windowService)
    {
        $this->windowService = $windowService;
    }

    public function dashboard()
    {
        $user         = Auth::user();
        $shield       = FacultyShield::firstOrCreate(['user_id' => $user->id], [
            'is_active'                   => true,
            'auto_reply_text'             => "Hello. {$user->name} is currently in a scheduled KU synchronized window. Your message has been received and will be delivered once the window concludes. All students receive queued messages simultaneously.",
            'allow_student_crisis_bypass' => true,
        ]);
        $activeWindow = $this->windowService->getActiveWindow();
        $nextWindow   = $this->windowService->getNextWindow();
        $queuedCount  = NotificationQueue::whereNull('released_at')->count();
        $windows      = KuWindow::all();

        // Burnout analytics data (simulated)
        $burnoutData = [
            'current_index'      => 31,
            'prev_index'         => 71,
            'weekly_hours'       => 44.5,
            'after_hours_msgs'   => 187,
            'compliance_score'   => 98,
        ];

        return view('faculty.dashboard', compact(
            'shield', 'activeWindow', 'nextWindow', 'queuedCount', 'windows', 'burnoutData'
        ));
    }

    public function shield()
    {
        $user   = Auth::user();
        $shield = FacultyShield::firstOrCreate(['user_id' => $user->id], [
            'is_active'       => true,
            'auto_reply_text' => "Hello. {$user->name} is currently in a scheduled KU synchronized window.",
        ]);
        return view('faculty.shield', compact('shield'));
    }

    public function updateShield(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'is_active'                   => 'boolean',
            'auto_reply_text'             => 'required|string|max:500',
            'allow_student_crisis_bypass' => 'boolean',
        ]);

        $shield = FacultyShield::firstOrCreate(['user_id' => $user->id]);
        $shield->update([
            'is_active'                   => $request->boolean('is_active'),
            'auto_reply_text'             => $data['auto_reply_text'],
            'allow_student_crisis_bypass' => $request->boolean('allow_student_crisis_bypass'),
        ]);

        return back()->with('success', 'Faculty Shield settings updated.');
    }

    public function queue()
    {
        $held     = NotificationQueue::whereNull('released_at')->latest('held_at')->get();
        $released = NotificationQueue::whereNotNull('released_at')->latest('released_at')->take(20)->get();
        return view('faculty.queue', compact('held', 'released'));
    }

    public function analytics()
    {
        $windows = KuWindow::with('analytics')->get();
        return view('faculty.analytics', compact('windows'));
    }

    public function schedule()
    {
        $windows = KuWindow::orderByRaw("CASE day_of_week WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3 WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6 WHEN 'Sunday' THEN 7 ELSE 8 END")->get();
        $activeWindow = $this->windowService->getActiveWindow();
        $nextWindow   = $this->windowService->getNextWindow();
        return view('faculty.schedule', compact('windows', 'activeWindow', 'nextWindow'));
    }
}
