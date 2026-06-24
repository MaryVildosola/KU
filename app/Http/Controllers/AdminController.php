<?php

namespace App\Http\Controllers;

use App\Models\KuWindow;
use App\Models\NotificationQueue;
use App\Models\AccommodationRequest;
use App\Models\FomoRiskScore;
use App\Models\WindowAnalytic;
use App\Models\User;
use App\Models\ResearchDataset;
use App\Services\WindowService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected WindowService $windowService;

    public function __construct(WindowService $windowService)
    {
        $this->windowService = $windowService;
    }

    public function dashboard()
    {
        $activeWindow   = $this->windowService->getActiveWindow();
        $nextWindow     = $this->windowService->getNextWindow();
        $latestFomo     = FomoRiskScore::latest('computed_at')->first();
        $totalWindows   = KuWindow::count();
        $totalAnalytics = WindowAnalytic::all();
        $pendingAccomm  = AccommodationRequest::where('status', 'pending')->count();
        $facultyCount   = User::where('role', 'faculty')->count();

        $kpis = [
            'after_hours_lms_reduction'  => 65,
            'focus_session_increase'     => 40,
            'faculty_comms_reduction'    => 55,
            'stress_reduction'           => 30,
            'midnight_lms_reduction'     => 70,
            'faculty_retention_increase' => 12,
        ];

        $recentAnalytics = WindowAnalytic::with('window')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'activeWindow', 'nextWindow', 'latestFomo',
            'totalWindows', 'pendingAccomm', 'facultyCount',
            'kpis', 'recentAnalytics'
        ));
    }

    public function windows()
    {
        $windows = KuWindow::with(['analytics' => fn($q) => $q->latest()->limit(1)])->get();
        $activeWindow = $this->windowService->getActiveWindow();
        return view('admin.windows.index', compact('windows', 'activeWindow'));
    }

    public function createWindow()
    {
        return view('admin.windows.create');
    }

    public function storeWindow(Request $request)
    {
        $data = $request->validate([
            'label'        => 'required|string|max:100',
            'day_of_week'  => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'   => 'required',
            'end_time'     => 'required',
            'description'  => 'nullable|string',
            'estimated_participants' => 'nullable|integer',
            'is_recurring' => 'boolean',
        ]);

        $data['is_recurring'] = $request->boolean('is_recurring', true);
        $data['estimated_participants'] = $data['estimated_participants'] ?? 42000;

        KuWindow::create($data);

        return redirect()->route('admin.windows')->with('success', 'KU Window created successfully.');
    }

    public function editWindow(KuWindow $window)
    {
        return view('admin.windows.edit', compact('window'));
    }

    public function updateWindow(Request $request, KuWindow $window)
    {
        $data = $request->validate([
            'label'        => 'required|string|max:100',
            'day_of_week'  => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'   => 'required',
            'end_time'     => 'required',
            'description'  => 'nullable|string',
            'estimated_participants' => 'nullable|integer',
        ]);

        $window->update($data);

        return redirect()->route('admin.windows')->with('success', 'Window updated.');
    }

    public function destroyWindow(KuWindow $window)
    {
        $window->delete();
        return redirect()->route('admin.windows')->with('success', 'Window deleted.');
    }

    public function triggerWindow(Request $request, KuWindow $window)
    {
        if ($window->is_active) {
            $this->windowService->endWindow($window);
            return back()->with('success', 'KU Window concluded. Notifications released simultaneously.');
        } else {
            $this->windowService->triggerManualWindow($window);
            return back()->with('success', 'KU Window started. Institutional silence begins now.');
        }
    }

    public function simulator()
    {
        return view('admin.windows.simulator');
    }

    public function simulatorRun(Request $request)
    {
        $data = $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'  => 'required',
            'end_time'    => 'required',
        ]);

        $result = $this->windowService->simulateWindowImpact(
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time']
        );

        return response()->json($result);
    }

    public function analytics()
    {
        $analytics  = WindowAnalytic::with('window')->latest()->take(12)->get();
        $fomoScores = FomoRiskScore::orderBy('computed_at')->get();
        $datasets   = ResearchDataset::all();
        return view('admin.analytics', compact('analytics', 'fomoScores', 'datasets'));
    }

    public function fomoDiagnostic()
    {
        $scores  = FomoRiskScore::orderBy('computed_at')->get();
        $latest  = $scores->last();
        $history = $scores->take(-8);
        return view('admin.fomo', compact('scores', 'latest', 'history'));
    }

    public function runFomoDiagnostic()
    {
        $score = $this->windowService->computeFomoRiskIndex();
        return back()->with('success', "FOMO Risk Index recomputed: {$score->composite_score} ({$score->risk_level})");
    }

    public function policies()
    {
        return view('admin.policies');
    }

    public function accommodations()
    {
        $requests = AccommodationRequest::latest()->get();
        return view('admin.accommodations', compact('requests'));
    }

    public function updateAccommodation(Request $request, AccommodationRequest $accommodation)
    {
        $data = $request->validate([
            'status'      => 'required|in:pending,approved,denied,under_review',
            'admin_notes' => 'nullable|string',
        ]);

        $accommodation->update(array_merge($data, [
            'reviewed_at' => now(),
        ]));

        return back()->with('success', 'Accommodation request updated.');
    }

    public function users()
    {
        $faculty     = User::where('role', 'faculty')->with('shield')->get();
        $researchers = User::where('role', 'researcher')->get();
        return view('admin.users', compact('faculty', 'researchers'));
    }
}
