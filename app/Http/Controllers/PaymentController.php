<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'coach']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->type) {
            $query->where('payment_type', $request->type);
        }
        
        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);
        $students = User::where('role', 'student')->orWhere('role', 'coach')->get();
        $coaches = User::where('role', 'coach')->orWhere('role', 'admin')->get();
        
        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending' => Payment::where('status', 'pending')->sum('amount'),
            'this_month' => Payment::where('status', 'completed')
                ->whereMonth('payment_date', Carbon::now()->month)
                ->sum('amount'),
        ];

        return inertia('Payments', [
            'payments' => $payments,
            'students' => $students,
            'coaches' => $coaches,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string',
            'status' => 'required|string',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Payment::create($request->all());

        return back()->with('success', 'Payment recorded');
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());
        return back()->with('success', 'Payment updated');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Payment deleted');
    }
}
