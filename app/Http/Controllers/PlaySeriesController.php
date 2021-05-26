<?php

namespace App\Http\Controllers;

use App\Model\PlaySeries;
use App\Model\Stockist;
use App\Model\StockistToTerminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PlaySeriesController extends Controller
{

    public function getPlaySeries(){
        $allPlaySeries = PlaySeries::all();
        echo json_encode($allPlaySeries,JSON_NUMERIC_CHECK);
    }

    public function setGamePayout(request $request){
        try
        {
            $requestedData = (object)($request->json()->all());
            $payoutValue= $requestedData->payoutValue;
            $resultPayout = DB::table('result_payout')->first();
            if(empty($resultPayout)){
                DB::table('result_payout')->insert(['payout_status'=>$payoutValue]);
            }else{
                DB::table('result_payout')->where('id',$resultPayout->id)->update(['payout_status'=>$payoutValue]);
            }
            DB::commit();
        }

        catch (Exception $e)
        {
            DB::rollBack();
            return response()->json(array('success' => 0, 'message' => $e->getMessage().'<br>File:-'.$e->getFile().'<br>Line:-'.$e->getLine()),401);
        }
        return response()->json(array('success' => 1, 'message' => 'Successfully recorded'),200);
    }

    public function getGamePayout(){

        $resultPayout = DB::table('result_payout')->first();
        echo json_encode($resultPayout,JSON_NUMERIC_CHECK);
    }

}
