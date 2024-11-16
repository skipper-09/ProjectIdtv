<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Fee_claim;
use App\Models\DetailClaim;
use App\Models\Reseller;
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
            'reseller' => Reseller::all()
        ];
        return view('pages.keuangan.fee-claim.index', $data);
    }


    public function getData(Request $request)
    {
        if ($request->has('filter') && !empty($request->input('filter'))) {
            $fee = Fee_claim::where('reseller_id', $request->input('filter'))->orderByDesc('id')->get();
        } else {
            $fee = Fee_claim::orderByDesc('id')->get();
        }
        return DataTables::of($fee)->addIndexColumn()->addColumn('action', function ($fee) {
            $button = '';
            if ($fee->status == 'pending') {
                $button .= ' <a href="' . route('feeclaim.show', ['id' => $fee->id]) . '" class="btn btn-sm btn-success action mr-1 " data-id=' . $fee->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Prses Data"><i
                                                                class="fa-solid fa-eye"></i></a>';
            } else {
                $button = '<span class="badge badge-success">Sudah Di proses</span>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('bank_name', function ($data) {
            return $data->reseller->bank->name;
        })->editColumn('rekening', function ($data) {
            return $data->reseller->rekening;
        })->editColumn('owner_rek', function ($data) {
            return $data->reseller->owner_rek;
        })->editColumn('amount', function ($data) {
            return number_format($data->amount);
        })->editColumn('reseller', function ($data) {
            return $data->reseller->name;
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
                ->format('Y-m-d H:i:s');;
        })->rawColumns(['action', 'rekening', 'bank_name', 'owner_rek', 'created_at', 'reseller', 'amount', 'status'])->make(true);
    }

    public function getDataDetailClaim(Request $request)
    {
        $id = $request->input('id');

        $detailclaims = DetailClaim::with(['subscribe', 'feeclaim'])->where('feeclaim_id', $id)->get();

        return DataTables::of($detailclaims)
            ->addIndexColumn()
            ->addColumn('customer_name', function ($detailclaim) {
                return $detailclaim->subscribe->customer->name ?? 'N/A';
            })
            ->addColumn('region', function ($detailclaim) {
                return $detailclaim->subscribe->customer->region->name ?? 'N/A';
            })
            ->addColumn('customer_address', function ($detailclaim) {
                return $detailclaim->subscribe->customer->address ?? 'N/A';
            })
            ->addColumn('start_date', function ($detailclaim) {
                return $detailclaim->subscribe->start_date ?? 'N/A';
            })
            ->addColumn('end_date', function ($detailclaim) {
                return $detailclaim->subscribe->end_date ?? 'N/A';
            })
            ->addColumn('paket', function ($detailclaim) {
                return $detailclaim->subscribe->resellerpaket->name ?? 'N/A';
            })
            ->addColumn('harga', function ($detailclaim) {
                return number_format($detailclaim->subscribe->resellerpaket->total) ?? 'N/A';
            })
            ->make(true);
    }



    public function show($id)
    {
        // Find the fee claim or fail
        $feeclaim = Fee_claim::findOrFail($id);

        // Get detail claim with eager loading of subscribe relationship and its nested customer
        $detailclaim = DetailClaim::with(['subscribe.customer', 'subscribe.resellerpaket'])
            ->where('feeclaim_id', $id)
            ->first();

        $data = [
            'type_menu' => 'Keungan',
            'page_name' => 'Proses' . ' ' . $feeclaim->reseller->name,
            'feeclaim' => $feeclaim,
            'customer' => $detailclaim->subscribe->customer,
            'detailclaim' => $detailclaim,
        ];

        return view('pages.keuangan.fee-claim.prosesclaim', $data);
    }

    public function Aprove(Request $request, $id)
    {

        $request->validate([
            'status' => 'required',
            // 'buktitf' => 'required',
        ], [
            // 'buktitf.required' => 'Bukti Transfer Tidak Boleh Kosong!',
            'status.required' => 'Status Tidak Boleh Kosong!',
        ]);

        $feeclaim = Fee_claim::findOrFail($id);

        $filename = '';
        if ($request->hasFile('buktitf')) {
            $file = $request->file('buktitf');
            $filename = 'buktitransfer_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images/buktitf/'), $filename);
        }

        // $detailclaim = DetailClaim::with(['subscribe.customer', 'subscribe.resellerpaket'])
        //     ->where('feeclaim_id', $id)
        //     ->first();

        // If status is rejected, update all related subscriptions is_claim to 0
        if ($request->status === 'rejected') {
            // Ambil semua detail klaim yang terkait dengan fee claim ini
            $detailClaims = DetailClaim::where('feeclaim_id', $id)->get();

            foreach ($detailClaims as $detailClaim) {
                if ($detailClaim->subscribe) {
                    // Update is_claim pada subscription
                    $detailClaim->subscribe->update([
                        'is_claim' => 0
                    ]);
                }
            }
        }



        $feeclaim->update([
            'reseller_id' => $feeclaim->reseller_id,
            'amount' => $feeclaim->amount,
            'status' => $request->status,
            'bukti_tf' => $filename,
        ]);
        return redirect()->route('feeclaim')->with(['status' => 'Success!', 'message' => 'Berhasil Proses Request Claim!']);
    }
}
