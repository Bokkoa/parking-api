<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Http\Requests\Parking\CheckExitRequest;
use App\Http\Requests\Parking\CheckEntranceRequest;
use Illuminate\Http\JsonResponse;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(CheckEntranceRequest $request)
    {
        $parking = Parking::create( $request->all() );

        return response()->json(['parking' => $parking ], JsonResponse::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function show(Parking $parking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function edit(Parking $parking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function update(CheckExitRequest $request, $id)
    {
        $parkingResult = Parking::checkExit( $request, $id );

        if( !$parkingResult ){
            return response()->json(['message' => 'La fecha de entrada es más reciente que la de salida.'], 
                                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json(['result' => $parkingResult], JsonResponse::HTTP_OK );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parking $parking)
    {
        //
    }

    public function monthlyReport(){

        $report = Parking::pentionMonthlyPayments();
        return response()->json(['report' => $report], JsonResponse::HTTP_OK);

    }
}
