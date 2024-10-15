<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\DetailClaim;
use App\Models\Fee_claim;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PendapatanController extends Controller
{
    public function index()
    {
        $company = Company::where('user_id', auth()->id())->first();
        $canclaim = Subscription::whereHas('customer', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->where('is_claim',0)->sum('fee');

        $data = [
            'type_menu' => 'layout',
            'page_name' => 'Pendapatan',
            'claim' => $canclaim,
        ];
        return view('pages.reseller.pendapatan.index', $data);
    }


    public function getData()
    {
        $company = Company::where('user_id', auth()->id())->first();
        $payment = Payment::with(['customer', 'subscrib'])->whereHas('subscrib', function ($query) {
            $query->where('status', true);
        })->whereHas('customer', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->whereHas('subscrib', function ($query) {
            $query->where('is_claim', false);
        })->get();
        return DataTables::of($payment)->addIndexColumn()->addColumn('action', function ($item) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('delete-customer')) {
                $button .= ' <button class="btn btn-sm btn-warning action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Re New Pelanggan"><i
                                                     class="fa-solid fa-bolt"></i></button>';
            }
            if ($userauth->can('update-customer')) {
                $button .= ' <a href="' . route('print.standart', ['id' => $item->id, 'type' => 'income']) . '" class="btn btn-sm btn-success action mr-1" target="_blank" data-id=' . $item->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="PRINT INVOICE"><i
                                                class="fa-solid fa-print"></i></a>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('nik', function ($data) {
            return $data->customer->nik;
        })->editColumn('customer', function ($data) {
            return $data->customer->name;
        })->editColumn('owner', function ($data) {
            return $data->customer->company->name;
        })->editColumn('paket', function ($data) {
            return $data->subscrib->paket->name . ' - ' . 'Rp. ' . number_format($data->subscrib->paket->price);
        })->editColumn('fee', function ($data) {
            return 'Rp. ' . number_format($data->subscrib->fee);
        })->editColumn('claim', function ($data) {
            $span = '';
            if ($data->subscrib->is_claim == true) {
                $span = '<span class="badge badge-primary">Sudah Di Claim</span>';
            } else {
                $span = '<span class="badge badge-success">Belum Di Claim</span>';
            }
            return $span;

        })->editColumn('start_date', function ($data) {
            return
                $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date;
        })->editColumn('end_date', function ($data) {
            return
                $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->end_date;
        })->editColumn('created_at', function ($data) {
            return
                Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at, 'UTC')
                    ->setTimezone(config('app.timezone'))
                    ->format('Y-m-d H:i:s');
        })->editColumn('status', function ($data) {
            $span = '';
            if ($data->status == 'paid') {
                $span = '<span class="badge badge-success">Lunas</span>';
            } else if ($data->status == 'unpaid') {
                $span = '<span class="badge badge-danger">Belum Bayar</span>';
            } else {
                $span = '<span class="badge badge-warning">Pending</span>';
            }
            return $span;
        })->rawColumns(['action', 'customer', 'status', 'paket', 'nik', 'start_date', 'owner', 'fee', 'claim'])->make(true);
    }




    //reqclaim pendapatan reseller

    public function reqClaim()
    {
        $company = Company::where('user_id', auth()->id())->first();
        $payment = Payment::with(['customer', 'subscrib'])->whereHas('subscrib', function ($query) {
            $query->where('status', true);
        })->whereHas('customer', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->whereHas('subscrib', function ($query) {
            $query->where('is_claim', 0);
        })->sum('fee');
        $data = [
            'categori' => null,
            'type_menu' => 'layout',
            'page_name' => 'Claim Pendapatan',
            'amount' => $payment,
            'company' => $company,
        ];
        return view('pages.reseller.pendapatan.reqclaim', $data);
    }

    public function storeClaim(Request $request)
    {
        try {
            $subsid = $request->input('subscribe_id');

            if (!$subsid || count($subsid) == 0) {
                return redirect()->back()->with(['status' => 'Error!', 'message' => 'Tidak Ada Data untuk di claim']);
            }

            $company = Company::where('user_id', Auth::id())->first();
            $claim = Fee_claim::create([
                'company_id' => $company->id,
                'amount' => $request->amount ?? 0,
                'status'=>'pending'
            ]);

            foreach ($subsid as $id) {
                //inset in table detail claim
                Subscription::findOrFail($id)->update([
                    'is_claim'=>1,
                ]);

                DetailClaim::create([
                   'subscription_id' => $id,
                   'feeclaim_id' => $claim->id,
                ]);
            }
            return redirect()->route('reseller.historyclaim')->with(['status' => 'Success!', 'message' => 'Request Claim Sedang di proses!']);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }



    //history claim
    public function HistoryClaim(){
        try {
            $company = Company::where('user_id', auth()->id())->first();
        $canclaim = Subscription::whereHas('customer', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->where('is_claim',true)->sum('fee');

        $data = [
            'type_menu' => 'layout',
            'page_name' => 'History Claim',
            'claim' => $canclaim,
        ];
        return view('pages.reseller.pendapatan.historyclaim', $data);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);        }
    }


    public function detail($id)
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Claim',
            'detail' => Fee_claim::find($id), 
        ];
        return view('pages.reseller.pendapatan.detail', $data);
    }


    public function GetHistory(){
        $fee = Fee_claim::orderByDesc('id')->get();
        return DataTables::of($fee)->addIndexColumn()->addColumn('action', function ($fee) {
            
            $button = '';
        if ($fee->status == 'pending') {
            $button = '<span class="badge badge-success">Sedang dicek</span>';
        }else{
            $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $fee->id . ' data-type="show" data-route="' . route('reseller.detail', ['id' => $fee->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
            class="fas fa-eye"></i></button>';
        }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('bank_name', function ($data) {
            return $data->company->bank_name;
        })->editColumn('rekening', function ($data) {
            return $data->company->rekening;
        })->editColumn('owner_rek', function ($data) {
            return $data->company->owner_rek;
        })->editColumn('amount', function ($data) {
            return number_format($data->amount);
        })->editColumn('company', function ($data) {
            return $data->company->name;
        })->editColumn('status', function ($data) {
            $span = '';
            if ($data->status == 'pending') {
                $span = '<span class="badge badge-warning">Pending</span>';
            } else if ($data->status == 'aproved') {
                $span = '<span class="badge badge-success">Aproved</span>';
            } else {
                $span = '<span class="badge badge-danger">Rejected</span>';
            }
            return $span;
        })->editColumn('created_at', function ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)
                ->setTimezone(config('app.timezone'))
                ->format('Y-m-d H:i:s');
            ;
        })->rawColumns(['action', 'rekening', 'bank_name', 'owner_rek', 'created_at', 'company', 'amount', 'status'])->make(true);
    }
}
