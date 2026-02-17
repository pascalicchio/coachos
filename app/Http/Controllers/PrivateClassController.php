<?php

namespace App\Http\Controllers;

use App\Models\PrivateClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrivateClassController extends Controller
{
    public function index(Request $request)
    {
        $query = PrivateClass::with(['student', 'coach']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $classes = $query->orderBy('scheduled_at', 'desc')->paginate(20);
        $students = User::where('role', 'student')->get();
        $coaches = User::where('role', 'coach')->orWhere('role', 'admin')->get();

        $stats = [
            'total' => PrivateClass::count(),
            'scheduled' => PrivateClass::where('status', 'scheduled')->count(),
            'completed' => PrivateClass::where('status', 'completed')->count(),
            'revenue' => PrivateClass::where('status', 'completed')->sum('price'),
        ];

        return inertia('PrivateClasses', [
            'classes' => $classes,
            'students' => $students,
            'coaches' => $coaches,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'coach_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        PrivateClass::create($request->all());

        return back()->with('success', 'Private class scheduled');
    }

    public function update(Request $request, PrivateClass $privateClass)
    {
        $request->validate([
            'status' => 'sometimes|in:scheduled,completed,cancelled,no_show',
        ]);

        $privateClass->update($request->all());

        return back()->with('success', 'Private class updated');
    }

    public function destroy(PrivateClass $privateClass)
    {
        $privateClass->delete();
        return back()->with('success', 'Private class deleted');
    }
}
