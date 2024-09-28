<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Fee_claim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class FeeClaimController extends Controller
{

    public function index()
    {
        $data = [
            'type_menu' => 'Keungan',
            'page_name' => 'Fee Reseller',
        ];
        return view('pages.keuangan.fee-claim.index', $data);
    }


    public function getData()
    {
        $fee = Fee_claim::all();
        return DataTables::of($fee)->addIndexColumn()->addColumn('action', function ($fee) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-company')) {
                $button .= ' <a href="' . route('company.edit', ['id' => $fee->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $fee->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-fee')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $fee->id . ' data-type="delete" data-route="' . route('company.delete', ['id' => $fee->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('owner_id', function ($data) {
            return $data->owner->name;
        })->editColumn('fee_reseller', function ($data) {
            return 'Rp ' . number_format($data->fee_reseller);
        })->rawColumns(['action', 'owner_id', 'fee_reseller'])->make(true);
    }
}
