<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Asesor;
use App\Models\Ventas;
use App\Models\Customer;
use App\Models\Zonas;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Log;


class VentaController extends Controller{
    

    public function __construct(){

    }


    public function dashboard(){



        if(Auth::user()->zone != NULL){
            $zona = Zonas::where(['id' => Auth::user()->zone])->with('ciudades')->get();

            Log::info($zona);  


            $customers = Customer::where(['is_asesor' => 0, 'city' => Auth::user()->zone])->count();
            $asesores  = Asesor::where(['ciudad' => Auth::user()->zone])->count();
            $ventas    = Ventas::where(['city' => Auth::user()->zone])->with('package')->get();
        }else{
            $customers = Customer::where(['is_asesor' => 0])->count();
            $asesores  = Asesor::all()->count();
            $ventas    = Ventas::with('package')->get();
        }


        $today = now();
        $startDate = $today->copy()->startOfMonth();
        $endDate = $today->copy()->endOfMonth();

        $sellmonthSeries = array();
        $monthDates = array();
        $sellcountForCurrentDay = array();
        $monthSeries = []; // Array to store counts for each month


        for($day = $startDate->copy(); $day->lte($endDate); $day->addDay()) {
            
            $sellmonthSeries = array_fill(0, 12, 0); 

            $ventas->each(function ($venta) use (&$sellmonthSeries) {
                    $monthIndex = Carbon::parse($venta->created_at)->format('n') - 1; // Get the month index (0-11)
                    $sellmonthSeries[$monthIndex] += $venta->package->price;
            });


            $weekDates = array();



            $sellweekpropertyCounts = DB::table('rc_ventas')
                    ->select(
                        DB::raw('DAYOFWEEK(rc_ventas.created_at) as day_of_week'),
                        DB::raw('SUM(packages.price) as count'),
                    )
                    ->where('city', Auth::user()->zone)
                    ->join('packages','rc_ventas.plan', 'packages.id')
                    ->groupBy(DB::raw('DAYOFWEEK(rc_ventas.created_at)'))
                    ->get();

            $sellweekSeries = array_fill(1, 7, 0);


            foreach ($sellweekpropertyCounts as $count) {
                $sellweekSeries[$count->day_of_week] = $count->count;
            }


            $ventasForDay = $ventas->filter(function ($venta) use ($day) {
                return $day->isSameDay(Carbon::parse($venta->created_at));
            });

            $countForMonth = $ventasForDay->sum('package.price');
            array_push($sellcountForCurrentDay, $countForMonth);

            $currentDates[] = '"' . Carbon::parse($day)->format('Y-m-d') . '"';
        }

        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::create(null, $month, 1)->format('M');
             \array_push($monthDates, "'" . $monthName . "'");
        }

        $chartData = [
            'sellmonthSeries' => $sellmonthSeries,
            'sellcountForCurrentDay' => $sellcountForCurrentDay,
            'sellweekSeries' => $sellweekSeries,
            'weekDates' =>  [0 => "'Day1'", 1 => "'Day2'", 2 => "'Day3'", 3 => "'Day4'", 4 => "'Day5'", 5 => "'Day6'", 6 => "'Day7'"],
            'monthDates' =>  $monthDates,
            'currentDates' => $currentDates,
            'currentDate' => "[" . Carbon::now()->format('Y-m-d') . "]"

        ];


        $sum_ventas = 0;    

        foreach ($ventas as $venta) {
            $sum_ventas += $venta->package->price; 
        }



        $paquetes = DB::table('rc_ventas')->select('packages.name',DB::raw('COUNT(*) as vendidos'))
                            ->join('packages','rc_ventas.plan', 'packages.id')
                            ->groupBy('packages.name')
                            ->get();



        return view('ventas/dashboard', compact('asesores','sum_ventas', 'customers','chartData', 'paquetes'));
    }

}