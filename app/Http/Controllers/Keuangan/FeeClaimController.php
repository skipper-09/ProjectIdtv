<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Fee_claim;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class FeeClaimController extends Controller
{

    public function index()
    {
        $data = [
            'type_menu' => 'Keungan',
            'page_name' => 'Fee Reseller',
            'company' => Company::all()
        ];
        return view('pages.keuangan.fee-claim.index', $data);
    }


    public function getData(Request $request)
    {
        if ($request->has('filter') && !empty($request->input('filter'))) {
            $fee = Fee_claim::where('company_id',$request->input('filter'))->orderByDesc('id')->get();
        }else{
            $fee = Fee_claim::orderByDesc('id')->get();
        }
        return DataTables::of($fee)->addIndexColumn()->addColumn('action', function ($fee) {
            $button = '';
            if ($fee->status == 'pending') {
                    $button .= ' <a href="' . route('feeclaim.show', ['id' => $fee->id]) . '" class="btn btn-sm btn-success action mr-1 " data-id=' . $fee->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Prses Data"><i
                                                                class="fa-solid fa-eye"></i></a>';
            }else{
                $button = '<span class="badge badge-success">Sudah Di proses</span>';
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


    public function show($id)
    {
        $feeclaim = Fee_claim::findOrFail($id);
        $data = [
            'type_menu' => 'Keungan',
            'page_name' => 'Proses' . ' ' . $feeclaim->company->name,
            'feeclaim' => $feeclaim,
        ];
        return view('pages.keuangan.fee-claim.prosesclaim', $data);
    }



    public function Aprove(Request $request, $id)
    {
        try {

            $feeclaim = Fee_claim::findOrFail($id);
            $filename = '';
            if ($request->hasFile('buktitf')) {
                $file = $request->file('buktitf');
                $filename = 'buktitransfer_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/buktitf/'), $filename);
            }

            $feeclaim->update([
                'company_id' => $feeclaim->company_id,
                'amount' => $feeclaim->amount,
                'status' => $request->status,
                'bukti_tf' => $filename,
            ]);
            return redirect()->route('feeclaim')->with(['status' => 'Success!', 'message' => 'Berhasil Proses Request Claim!']);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }
}
