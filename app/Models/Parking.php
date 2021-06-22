<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Parking extends Model
{
    use HasFactory;

    const HOUR_PRICE = 10;
    const PENSION_DISCOUNT = .1;
    const MINUTE_LIMIT = 10;

    protected $table = 'parkings';

    protected $fillable = [
        'entrance',
        'exit',
        'cost',
        'total_minutes',
        'created_at',
        'updated_at',
        'vehicle_id',
    ];

    public function vehicle(){

        return $this->belongsTo('App\Models\Vehicle', 'vehicle_id', 'license_plate');

    }
    
    public static function checkExit( $request, $id ) {

        $parking = Parking::findOrFail( $id );
        $parking->exit = $request->exit;
        
        // getting client for type
        $client = $parking->vehicle->client;
        
        // using carbon library for date validation
        $entrance = Carbon::parse($parking->entrance);
        $exit = Carbon::parse( $parking->exit );

        // entrance is more recentily than exit
        if( $entrance->gt($exit) ){

            return false;

        }

        $parking->total_minutes = $exit->diffInMinutes($entrance);
        
        // pension process
        if( strtolower($client->name) === 'pensión' || 
            strtolower( $client->name ) === 'pension' ){
            
            $exit->minute(0);
            $totalHours = $exit->diffInHours($entrance);
            $cost = ($totalHours * self::HOUR_PRICE ) * self::PENSION_DISCOUNT;
            
            $parking->cost = $cost;
            $parking->save();

            return $cost;
            
        }

        // normal process
        if( $exit->minute > self::MINUTE_LIMIT ){
            $exit->addHour();
        }

        $totalHours = $exit->diffInHours($entrance);

        $cost = $totalHours * self::HOUR_PRICE;
        
        $parking->cost = $cost;
        $parking->save();

        return $cost;

    }

    public static function pentionMonthlyPayments(){
        
        $currentMonth = date('m');
        $currentYear = date('Y');

        $payments = Parking::whereYear('exit', $currentYear)
                            ->whereMonth('exit', $currentMonth)
                            ->sum('cost');
        $report = ['month' => $currentMonth,
                   'year' => $currentYear,
                   'total_payment' => $payments ];

        return $report;
    }


}
