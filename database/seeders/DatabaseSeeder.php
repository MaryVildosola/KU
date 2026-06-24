<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KuWindow;
use App\Models\NotificationQueue;
use App\Models\AccommodationRequest;
use App\Models\FomoRiskScore;
use App\Models\WindowAnalytic;
use App\Models\FacultyShield;
use App\Models\ResearchDataset;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- USERS ---
        $admin = User::create([
            'name'            => 'Admin — KU',
            'email'           => 'admin@ku.edu',
            'password'        => Hash::make('password'),
            'role'            => 'admin',
            'department'      => 'Office of Academic Affairs',
            'position'        => 'Platform Administrator',
            'avatar_initials' => 'AD',
        ]);

        $santos = User::create([
            'name'            => 'Mr. Andres Santos',
            'email'           => 'santos@ku.edu',
            'password'        => Hash::make('password'),
            'role'            => 'faculty',
            'department'      => 'Faculty of Engineering',
            'position'        => 'Associate Professor',
            'avatar_initials' => 'AS',
        ]);

        $reyes = User::create([
            'name'            => 'Dr. Carla Reyes',
            'email'           => 'reyes@ku.edu',
            'password'        => Hash::make('password'),
            'role'            => 'faculty',
            'department'      => 'Faculty of Arts & Sciences',
            'position'        => 'Associate Professor',
            'avatar_initials' => 'CR',
        ]);

        $researcher = User::create([
            'name'            => 'Dr. James Lim',
            'email'           => 'researcher@ku.edu',
            'password'        => Hash::make('password'),
            'role'            => 'researcher',
            'department'      => 'Dept. of Psychology',
            'position'        => 'Senior Research Fellow',
            'avatar_initials' => 'JL',
        ]);

        // --- KU WINDOWS ---
        $win1 = KuWindow::create([
            'label'                  => 'Evening Silence — Monday',
            'day_of_week'            => 'Monday',
            'start_time'             => '20:00:00',
            'end_time'               => '21:00:00',
            'is_active'              => false,
            'triggered_manually'     => false,
            'is_recurring'           => true,
            'description'            => 'Monday evening synchronized window. LMS notification delivery suspended. Faculty auto-reply enabled.',
            'estimated_participants' => 42000,
        ]);

        $win2 = KuWindow::create([
            'label'                  => 'Evening Silence — Wednesday',
            'day_of_week'            => 'Wednesday',
            'start_time'             => '20:00:00',
            'end_time'               => '21:00:00',
            'is_active'              => false,
            'triggered_manually'     => false,
            'is_recurring'           => true,
            'description'            => 'Midweek collective rest period. Optimized for peak Wednesday connectivity load reduction.',
            'estimated_participants' => 42000,
        ]);

        $win3 = KuWindow::create([
            'label'                  => 'Sunday Deep Rest',
            'day_of_week'            => 'Sunday',
            'start_time'             => '14:00:00',
            'end_time'               => '17:00:00',
            'is_active'              => false,
            'triggered_manually'     => false,
            'is_recurring'           => true,
            'description'            => 'Extended Sunday afternoon recovery. Three-hour deep rest window with maximum deadline suspension.',
            'estimated_participants' => 38000,
        ]);

        // --- FACULTY SHIELDS ---
        FacultyShield::create([
            'user_id'                     => $santos->id,
            'is_active'                   => true,
            'auto_reply_text'             => 'Hello. Professor Santos is currently in a scheduled KU synchronized window. Your message has been received and will be delivered once the window concludes. All students receive queued messages simultaneously — no one has an advantage.',
            'allow_student_crisis_bypass' => true,
            'messages_queued_total'       => 147,
            'last_window_queued_at'       => now()->subDays(2),
        ]);

        FacultyShield::create([
            'user_id'                     => $reyes->id,
            'is_active'                   => true,
            'auto_reply_text'             => 'This is an automated KU response. Dr. Reyes is currently in a protected disconnection window. Your message will be delivered when the window ends. Thank you for respecting this institutional rest period.',
            'allow_student_crisis_bypass' => true,
            'messages_queued_total'       => 89,
            'last_window_queued_at'       => now()->subDays(2),
        ]);

        // --- WINDOW ANALYTICS ---
        WindowAnalytic::create([
            'ku_window_id'               => $win1->id,
            'participants'               => 41230,
            'notifications_held'         => 3842,
            'notifications_released'     => 3842,
            'avg_cri_improvement'        => 27.4,
            'faculty_participation_rate' => 94.2,
            'student_participation_rate' => 87.6,
            'emergency_bypasses'         => 14,
            'window_started_at'          => now()->subDays(7)->setTime(20, 0),
            'window_ended_at'            => now()->subDays(7)->setTime(21, 0),
        ]);

        WindowAnalytic::create([
            'ku_window_id'               => $win2->id,
            'participants'               => 40980,
            'notifications_held'         => 4120,
            'notifications_released'     => 4120,
            'avg_cri_improvement'        => 31.2,
            'faculty_participation_rate' => 96.1,
            'student_participation_rate' => 89.3,
            'emergency_bypasses'         => 9,
            'window_started_at'          => now()->subDays(5)->setTime(20, 0),
            'window_ended_at'            => now()->subDays(5)->setTime(21, 0),
        ]);

        WindowAnalytic::create([
            'ku_window_id'               => $win3->id,
            'participants'               => 38400,
            'notifications_held'         => 2210,
            'notifications_released'     => 2210,
            'avg_cri_improvement'        => 44.8,
            'faculty_participation_rate' => 91.5,
            'student_participation_rate' => 82.7,
            'emergency_bypasses'         => 6,
            'window_started_at'          => now()->subDays(1)->setTime(14, 0),
            'window_ended_at'            => now()->subDays(1)->setTime(17, 0),
        ]);

        // --- NOTIFICATION QUEUE (Sample held messages from last window) ---
        $notifications = [
            ['sender' => 'Canvas LMS', 'app_type' => 'lms',    'message' => 'New assignment posted: Midterm Essay — due Friday 11:59 PM.'],
            ['sender' => 'Canvas LMS', 'app_type' => 'lms',    'message' => 'Grade released: Laboratory Report 3 — view your score.'],
            ['sender' => 'Gmail',      'app_type' => 'email',  'message' => 'Registrar: Updated tuition billing statement for Second Semester.'],
            ['sender' => 'Prof. Cruz', 'app_type' => 'email',  'message' => 'Reminder: Office hours moved to Thursday 2–4 PM this week.'],
            ['sender' => 'Discord',    'app_type' => 'social', 'message' => 'Study group: "Did anyone finish problem set 4? The deadline is at midnight!"'],
        ];

        foreach ($notifications as $notif) {
            NotificationQueue::create([
                'ku_window_id'    => $win2->id,
                'sender'          => $notif['sender'],
                'recipient_group' => 'students',
                'recipient_count' => rand(38000, 42000),
                'app_type'        => $notif['app_type'],
                'message'         => $notif['message'],
                'held_at'         => now()->subDays(5)->setTime(20, rand(5, 55)),
                'released_at'     => now()->subDays(5)->setTime(21, 0),
            ]);
        }

        // --- FOMO RISK SCORES ---
        // Historical scores (improving trend)
        $fomoScores = [
            ['score' => 78.4, 'np' => 82.1, 'dc' => 71.2, 'ma' => 80.6, 'af' => 79.5, 'level' => 'high',     'date' => now()->subWeeks(8)],
            ['score' => 75.2, 'np' => 79.8, 'dc' => 68.4, 'ma' => 77.2, 'af' => 75.4, 'level' => 'high',     'date' => now()->subWeeks(7)],
            ['score' => 71.6, 'np' => 76.2, 'dc' => 65.1, 'ma' => 73.8, 'af' => 71.2, 'level' => 'high',     'date' => now()->subWeeks(6)],
            ['score' => 66.3, 'np' => 71.4, 'dc' => 59.8, 'ma' => 68.2, 'af' => 65.7, 'level' => 'moderate', 'date' => now()->subWeeks(5)],
            ['score' => 59.1, 'np' => 63.8, 'dc' => 53.2, 'ma' => 61.4, 'af' => 58.0, 'level' => 'moderate', 'date' => now()->subWeeks(4)],
            ['score' => 51.4, 'np' => 55.2, 'dc' => 47.8, 'ma' => 54.1, 'af' => 48.5, 'level' => 'moderate', 'date' => now()->subWeeks(3)],
            ['score' => 43.7, 'np' => 47.1, 'dc' => 39.4, 'ma' => 46.2, 'af' => 42.1, 'level' => 'moderate', 'date' => now()->subWeeks(2)],
            ['score' => 34.2, 'np' => 37.8, 'dc' => 29.6, 'ma' => 36.9, 'af' => 32.4, 'level' => 'low',      'date' => now()->subWeeks(1)],
        ];

        foreach ($fomoScores as $fs) {
            FomoRiskScore::create([
                'composite_score'       => $fs['score'],
                'notification_pressure' => $fs['np'],
                'deadline_clustering'   => $fs['dc'],
                'midnight_activity'     => $fs['ma'],
                'after_hours_faculty'   => $fs['af'],
                'risk_level'            => $fs['level'],
                'total_users_analyzed'  => rand(40000, 42500),
                'computed_at'           => $fs['date'],
            ]);
        }

        // --- ACCOMMODATION REQUESTS ---
        AccommodationRequest::create([
            'student_ref_id'   => 'STU-2026-0841',
            'student_name'     => 'Anonymous',
            'department'       => 'Engineering',
            'category'         => 'Employment',
            'notes'            => 'I work a part-time shift on Tuesday evenings from 7:30 to 9:30 PM. I would like to request that my Tuesday window be shifted to 10 PM.',
            'modified_schedule' => 'Tuesday: 10:00 PM – 11:00 PM',
            'status'           => 'approved',
            'admin_notes'      => 'Approved — schedule modification applied automatically.',
            'reviewed_at'      => now()->subDays(3),
        ]);

        AccommodationRequest::create([
            'student_ref_id'   => 'STU-2026-1120',
            'student_name'     => 'Anonymous',
            'department'       => 'Nursing',
            'category'         => 'Medical',
            'notes'            => 'I have a recurring medical appointment on Wednesday evenings at 8 PM. I need a window exemption or shift for this day.',
            'modified_schedule' => 'Wednesday: Exempt from 8–9 PM window',
            'status'           => 'pending',
            'admin_notes'      => null,
            'reviewed_at'      => null,
        ]);

        AccommodationRequest::create([
            'student_ref_id'   => 'STU-2026-0374',
            'student_name'     => 'Anonymous',
            'department'       => 'Psychology',
            'category'         => 'Disability',
            'notes'            => 'I have a documented anxiety condition and may need to access the crisis support line at any time. I am requesting that my emergency bypass always be active.',
            'modified_schedule' => 'Emergency bypass permanently active',
            'status'           => 'under_review',
            'admin_notes'      => 'Under review with Office of Student Welfare.',
            'reviewed_at'      => null,
        ]);

        // --- RESEARCH DATASETS ---
        ResearchDataset::create([
            'title'            => 'Sem 1 AY2025–2026: CRI Improvement Trends',
            'dataset_period'   => 'Sem 1 AY 2025-2026',
            'access_level'     => 'approved_only',
            'sample_size'      => 41823,
            'department_scope' => null,
            'description'      => 'Anonymized aggregate Cognitive Recovery Index trends measured before, during, and after synchronized KU windows. Includes weekly CRI baselines, recovery session activity types, and window participation rates.',
            'data_json'        => [
                'weeks' => ['Wk1','Wk2','Wk3','Wk4','Wk5','Wk6','Wk7','Wk8'],
                'avg_cri_baseline' => [34, 36, 39, 43, 51, 58, 64, 71],
                'avg_cri_post_window' => [48, 51, 55, 61, 69, 76, 81, 87],
                'window_participation_rate' => [72, 75, 78, 82, 85, 87, 89, 91],
            ],
        ]);

        ResearchDataset::create([
            'title'            => 'Faculty After-Hours Communication Load',
            'dataset_period'   => 'Sem 1 AY 2025-2026',
            'access_level'     => 'approved_only',
            'sample_size'      => 1204,
            'department_scope' => null,
            'description'      => 'Anonymized aggregate of faculty communication patterns after 9 PM. Tracks message volume, response-time expectations, and reported burnout index before and after KU adoption.',
            'data_json'        => [
                'weeks' => ['Wk1','Wk2','Wk3','Wk4','Wk5','Wk6','Wk7','Wk8'],
                'after_hours_messages' => [420, 398, 371, 340, 291, 248, 201, 187],
                'burnout_index' => [71, 68, 64, 59, 52, 44, 37, 31],
            ],
        ]);

        ResearchDataset::create([
            'title'            => 'Notification Volume: Before vs During KU Windows',
            'dataset_period'   => 'Sem 1 AY 2025-2026',
            'access_level'     => 'public',
            'sample_size'      => 41823,
            'department_scope' => null,
            'description'      => 'Institution-wide LMS and email notification volume comparison: baseline pre-KU adoption vs. during active windows. Publicly accessible dataset for policy reference.',
            'data_json'        => [
                'labels' => ['8 AM','10 AM','12 PM','2 PM','4 PM','6 PM','8 PM','10 PM','12 AM'],
                'pre_ku_avg' => [120, 240, 380, 310, 420, 510, 680, 590, 340],
                'during_window_avg' => [120, 240, 380, 310, 420, 510, 42, 38, 29],
            ],
        ]);
    }
}
