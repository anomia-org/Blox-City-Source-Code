<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class PromocodesController extends Controller
{
    private $codes = [
        '6853463683' => 103,
        '7626582327' => 778,
    ];

    public function index()
    {
        $codeItems = [Item::where('id', '=', 103)->first(), Item::where('id', '=', 778)->first()];

        return view('user.promocodes.index')->with([
            'codeItems' => $codeItems
        ]);
    }

    public function redeem(Request $request)
    {
        if (!$request->has('code')) {
            return response()->json(['success' => false, 'message' => 'Please provide a code.']);
        }

        if (auth()->user()->owns(Item::find($this->codes[$request->code]))) {
            return response()->json(['success' => false, 'message' => 'You have already redeemed this code.']);
        }

        $lockKey = 'lock:comment:' . $this->codes[$request->code];
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);

        if (!$lockAcquired) {
            return response()->json(['success' => false, 'message' => 'You are trying too fast. Please wait and try again.']);
        }

        $code = strtoupper($request->code);
        $arrayKey = array_search($code, $this->codes);

        if (in_array($arrayKey, $this->codes)) {
            return response()->json(['success' => false, 'message' => 'Invalid code.']);
        }
        try {
            DB::beginTransaction();

            auth()->user()->grant_item(Item::find($this->codes[$code]));
            DB::commit();
            Redis::del($lockKey);

            return response()->json(['success' => true, 'message' => 'Code has been successfully redeemed!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);

            return response()->json(['success' => false, 'message' => 'An error occurred while redeeming the code.']);
        }
    }
}
