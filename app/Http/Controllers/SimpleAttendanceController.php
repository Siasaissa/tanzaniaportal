<?php

namespace App\Http\Controllers;

use App\Models\SimpleAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SimpleAttendanceController extends Controller
{
    /**
     * Show the attendance form
     */
    public function index()
    {
        // Get today's attendance records
        $todayAttendances = SimpleAttendance::today()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        // Get total count
        $totalRecords = SimpleAttendance::count();
        $todayCount = $todayAttendances->count();
        
        return view('attendance.index', compact('todayAttendances', 'totalRecords', 'todayCount'));
    }

    /**
     * Store attendance record
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'date' => 'required|date',
            'work' => 'required|string|min:5|max:2000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill all fields correctly.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create attendance record
            $attendance = SimpleAttendance::create([
                'name' => $request->name,
                'date' => $request->date,
                'work' => $request->work,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Log the submission
            Log::info('Attendance submitted', [
                'name' => $request->name,
                'date' => $request->date,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully!',
                'data' => [
                    'id' => $attendance->id,
                    'name' => $attendance->name,
                    'date' => $attendance->formatted_date,
                    'work' => $attendance->work_summary
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving attendance: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save attendance. Please try again.'
            ], 500);
        }
    }

    /**
     * Get attendance records for a specific date
     */
    public function getByDate(Request $request)
    {
        $date = $request->query('date', today()->toDateString());
        
        $attendances = SimpleAttendance::forDate($date)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'date' => $date,
            'count' => $attendances->count(),
            'data' => $attendances->map(function($item) {
                return [
                    'name' => $item->name,
                    'work' => $item->work,
                    'time' => $item->created_at->format('h:i A'),
                    'date_formatted' => $item->formatted_date
                ];
            })
        ]);
    }

    /**
     * Search attendance records
     */
    public function search(Request $request)
    {
        $query = SimpleAttendance::query();
        
        if ($request->has('name')) {
            $query->forName($request->name);
        }
        
        if ($request->has('date')) {
            $query->forDate($request->date);
        }
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        $results = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'total' => $results->total(),
            'data' => $results->items()
        ]);
    }

    /**
     * Get statistics
     */
    public function statistics()
    {
        $today = today()->toDateString();
        $yesterday = today()->subDay()->toDateString();
        
        $stats = [
            'today_count' => SimpleAttendance::forDate($today)->count(),
            'yesterday_count' => SimpleAttendance::forDate($yesterday)->count(),
            'total_count' => SimpleAttendance::count(),
            'this_week' => SimpleAttendance::whereBetween('date', 
                [today()->startOfWeek(), today()->endOfWeek()]
            )->count(),
            'this_month' => SimpleAttendance::whereBetween('date',
                [today()->startOfMonth(), today()->endOfMonth()]
            )->count(),
            'unique_names' => SimpleAttendance::distinct('name')->count('name')
        ];
        
        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }

    /**
     * Export attendance data
     */
    public function export(Request $request)
    {
        $startDate = $request->query('start_date', today()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', today()->toDateString());
        
        $attendances = SimpleAttendance::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        if ($request->query('format') === 'csv') {
            return $this->exportToCsv($attendances, $startDate, $endDate);
        }
        
        // Default to JSON
        return response()->json([
            'success' => true,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'count' => $attendances->count(),
            'data' => $attendances
        ]);
    }
    
    /**
     * Export to CSV
     */
    private function exportToCsv($attendances, $startDate, $endDate)
    {
        $fileName = "attendance_{$startDate}_to_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];
        
        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Name', 'Date', 'Work', 'Time Submitted', 'IP Address']);
            
            // Add data rows
            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->name,
                    $attendance->date->format('Y-m-d'),
                    $attendance->work,
                    $attendance->created_at->format('h:i A'),
                    $attendance->ip_address
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}