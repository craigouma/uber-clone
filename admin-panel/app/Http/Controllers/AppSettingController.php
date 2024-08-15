<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppSetting;
class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = AppSetting::first();
        $data->stripe_key = env('STRIPE_KEY');
        $data->stripe_secret = env('STRIPE_API_KEY');
        $data->razorpay_key = env('RAZORPAY_KEY');
        $data->paystack_secret_key = env('PAYSTACK_SECRET_KEY');
        $data->paystack_public_key = env('PAYSTACK_PUBLIC_KEY');
        $data->flutterwave_public_key = env('FLUTTERWAVE_PUBLIC_KEY');
        $data->paypal_client_id = env('PAYPAL_SANDBOX_CLIENT_ID');
        $data->android_latest_version = DB::table('app_versions')->where('platform',1)->orderBy('id', 'desc')->first();
        $data->ios_latest_version = DB::table('app_versions')->where('platform',2)->orderBy('id', 'desc')->first();
        $data->mode = env('MODE');
        
        return response()->json([
            "result" => $data,
            "message" => 'Success',
            "status" => 1
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
