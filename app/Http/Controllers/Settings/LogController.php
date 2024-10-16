<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class LogController extends Controller
{
    public function index()
    {
        $data = [
            'user' => User::all(),
            'type_menu' => 'setting',
            'page_name' => 'Log Activity',
        ];
        return view('pages.settings.log.index', $data);
    }
    public function getData()
    {
        $activity = Activity::with('causer')->orderBy('created_at', 'desc')->get();
        return DataTables::of($activity)->addIndexColumn()->editColumn('causer', function ($data) {
            return $data->causer == null ? '-' : $data->causer->name;
        })->editColumn('created_at', function ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)
                ->setTimezone(config('app.timezone'))
                ->format('Y-m-d H:i:s');
        })->editColumn('description', function ($data) {
            $properties = json_decode($data->properties, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'Error decoding properties';
            }

            // Mengambil nilai lama dan baru dari data JSON
            $old = isset($properties['old']) ? $properties['old'] : [];
            $new = isset($properties['attributes']) ? $properties['attributes'] : [];

            // Membuat deskripsi perubahan berdasarkan event
            $descriptions = [];
            foreach ($new as $key => $newValue) {
                // Mengambil nilai lama, jika tidak ada berikan 'N/A'
                $oldValue = isset($old[$key]) ? $old[$key] : 'N/A';

                // Jika nilai baru adalah null, set sebagai 'N/A'
                if ($newValue === null) {
                    $newValue = 'N/A';
                }

                // Mengubah nama field menjadi format yang lebih baik
                $fieldLabel = ucwords(str_replace('_', ' ', $key));

                // Deteksi perubahan pada event 'update'
                if ($data->event === 'update') {
                    // Cek perubahan jika nilai lama dan baru berbeda
                    if ($oldValue != $newValue) {
                        $descriptions[] = "$fieldLabel di update dari <strong>$oldValue</strong> ke <strong>$newValue</strong>";
                    }
                } elseif ($data->event === 'created') {
                    $descriptions[] = "$fieldLabel dibuat dengan nilai <strong>$newValue</strong>";
                } elseif ($data->event === 'deleted') {
                    if ($oldValue !== 'N/A') {
                        $descriptions[] = "$fieldLabel dihapus dengan nilai <strong>$oldValue</strong>";
                    }
                }
            }

            // Jika ada deskripsi, gabungkan dan kembalikan, jika tidak ada perubahan, tampilkan 'No changes detected'
            if (!empty($descriptions)) {
                return implode('<br>', $descriptions);
            } else {
                // Jika hanya satu field yang di-update dan tidak terdeteksi, berikan peringatan untuk field yang berubah
                if (!empty($new)) {
                    return implode('<br>', ["$fieldLabel di update dari <strong>$oldValue</strong> ke <strong>$newValue</strong>"]);
                }
                return 'No changes detected';
            }
        })->rawColumns(['causer', 'created_at', 'description'])->make(true);
    }


    public function cleanlog()
    {
        try {
            Activity::truncate();
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Log Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }
}
