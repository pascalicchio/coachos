<?php

namespace App\Http\Controllers;

use App\Models\ClassTemplate;
use App\Models\ScheduledClass;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $templates = ClassTemplate::with('coach')->orderBy('day_of_week')->orderBy('start_time')->get();
        $scheduledClasses = ScheduledClass::with('coach')
            ->where('start_time', '>=', Carbon::now()->startOfWeek())
            ->orderBy('start_time')
            ->get();
        
        return inertia('Schedule', [
            'templates' => $templates,
            'scheduledClasses' => $scheduledClasses,
        ]);
    }

    public function generateSchedule(Request $request)
    {
        $request->validate([
            'weeks' => 'required|integer|min:1|max:12',
        ]);

        $templates = ClassTemplate::where('is_active', true)->get();
        $generated = [];
        
        $startDate = Carbon::now()->startOfWeek();
        
        for ($week = 0; $week < $request->weeks; $week++) {
            foreach ($templates as $template) {
                $classDate = $startDate->copy()->addWeeks($week)->addDays($template->day_of_week);
                $startDateTime = Carbon::parse($classDate->format('Y-m-d') . ' ' . $template->start_time);
                $endDateTime = Carbon::parse($classDate->format('Y-m-d') . ' ' . $template->end_time);
                
                $scheduled = ScheduledClass::create([
                    'class_name' => $template->class_name,
                    'start_time' => $startDateTime,
                    'end_time' => $endDateTime,
                    'coach_id' => $template->coach_id,
                    'class_template_id' => $template->id,
                    'location' => $template->location,
                    'martial_art' => $template->martial_art,
                ]);
                
                $generated[] = $scheduled;
            }
        }

        return back()->with('success', 'Generated ' . count($generated) . ' classes');
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required',
            'end_time' => 'required',
            'coach_id' => 'nullable',
            'location' => 'nullable',
            'martial_art' => 'nullable',
        ]);

        ClassTemplate::create($request->all());

        return back()->with('success', 'Class template created');
    }

    public function updateTemplate(Request $request, ClassTemplate $template)
    {
        $template->update($request->all());
        return back()->with('success', 'Template updated');
    }

    public function destroyTemplate(ClassTemplate $template)
    {
        $template->delete();
        return back()->with('success', 'Template deleted');
    }
}
