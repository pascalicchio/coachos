<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Lead;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\CSV;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function members()
    {
        $members = Member::with('membership')
            ->where('organization_id', auth()->user()->organization_id)
            ->get();

        $data = $members->map(function ($m) {
            return [
                'Name' => $m->name,
                'Email' => $m->email,
                'Phone' => $m->phone,
                'Membership' => $m->membership?->name,
                'Join Date' => $m->join_date,
                'Expiry Date' => $m->expiry_date,
                'Status' => $m->status,
            ];
        });

        return Response::make(CSV::fromArray($data->toArray()), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="members.csv"',
        ]);
    }

    public function leads()
    {
        $leads = Lead::where('organization_id', auth()->user()->organization_id)->get();

        $data = $leads->map(function ($l) {
            return [
                'Name' => $l->name,
                'Email' => $l->email,
                'Phone' => $l->phone,
                'Source' => $l->source,
                'Status' => $l->status,
                'Created' => $l->created_at,
            ];
        });

        return Response::make(CSV::fromArray($data->toArray()), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads.csv"',
        ]);
    }

    public function payments()
    {
        $payments = Payment::with('member')
            ->where('organization_id', auth()->user()->organization_id)
            ->where('status', 'completed')
            ->get();

        $data = $payments->map(function ($p) {
            return [
                'Date' => $p->paid_at,
                'Member' => $p->member?->name,
                'Amount' => $p->amount,
                'Type' => $p->type,
                'Method' => $p->payment_method,
                'Description' => $p->description,
            ];
        });

        return Response::make(CSV::fromArray($data->toArray()), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payments.csv"',
        ]);
    }
}
