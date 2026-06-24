<?php

namespace App\Http\Controllers;

use App\Models\ResearchDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ResearchController extends Controller
{
    public function dashboard()
    {
        $publicDatasets   = ResearchDataset::where('access_level', 'public')->get();
        $approvedDatasets = ResearchDataset::where('access_level', 'approved_only')->get();
        return view('research.dashboard', compact('publicDatasets', 'approvedDatasets'));
    }

    public function download(ResearchDataset $dataset)
    {
        // Generate CSV from data_json
        $data = $dataset->data_json;
        $csv  = '';

        if (isset($data['weeks'])) {
            $headers = ['Week'];
            $rows    = [];
            foreach ($data as $key => $values) {
                if ($key !== 'weeks') {
                    $headers[] = ucwords(str_replace('_', ' ', $key));
                    foreach ($values as $i => $val) {
                        $rows[$i][] = $val;
                    }
                }
            }
            $csv .= implode(',', $headers) . "\n";
            foreach ($data['weeks'] as $i => $week) {
                $row = [$week];
                foreach ($rows[$i] ?? [] as $v) $row[] = $v;
                $csv .= implode(',', $row) . "\n";
            }
        } else {
            foreach ($data as $key => $values) {
                $csv .= $key . ',' . implode(',', (array)$values) . "\n";
            }
        }

        $filename = str($dataset->title)->slug()->append('.csv')->value();

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function apply()
    {
        return view('research.apply');
    }

    public function submitApplication(Request $request)
    {
        $request->validate([
            'research_title'    => 'required|string|max:200',
            'institution'       => 'required|string|max:200',
            'research_purpose'  => 'required|string',
            'datasets_needed'   => 'required|string',
        ]);

        // In production: store to DB, notify admin
        return back()->with('success', 'Your dataset access application has been submitted. You will be notified within 5 business days.');
    }
}
