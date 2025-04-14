<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Log;
use App\Order;
use App\Reason;
use App\Status;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $status = 0;
        if( auth()->user()->role->name == 'Administrador' ){
            $orders = Order::all()->count();
            $users = User::all()->count();
            $logs = Log::orderBy('created_at','DESC')->paginate(15);
        }
        if( auth()->user()->role->name == 'Empleado' ){
            $orders = Order::all();
            if( auth()->user()->department->name == 'Ventas' ){
                $status = 1;
                $orders = Order::where('status_id', $status)
                                ->orWhere('status_id', 6)->get();
            }
            if( auth()->user()->department->name == 'Embarques' ){
                $status = 2;
                $orders = Order::where('status_id', $status)
                                ->orWhere('status_id', 6)->get();
            }
            if( auth()->user()->department->name == 'Fabricación' ){
                // $status = 4;
                $orders = Order::where('status_id', $status)->get();
            }
            if( auth()->user()->department->name == 'Flotilla' ){
                // $status = 5;
                $orders = Order::where('status_id', $status)->get();
            }
            if( auth()->user()->department->name == 'Compras' ){
                $orders = Order::where('status_id', 3)
                            ->orWhere('status_id', 4)
                            ->get();
            }

            $users = User::all()->count();
            $logs = Log::orderBy('created_at','DESC')->paginate(15);
        }
        if( auth()->user()->role->name == 'Cliente' ){
            $orders = Order::all()->count();
            $users = User::all()->count();
            $logs = Log::orderBy('created_at','DESC')->paginate(15);
        }
        $plural = 0;



        return view('dashboard', compact('orders', 'users', 'logs', 'status', 'plural'));
    }

    public function search(Request $request)
    {
        // dd($request->all());
        $orders = Order::all()->count();
        $users = User::all()->count();
        $logs = Log::orderBy('created_at','DESC')->paginate(15);
        // dd($order);
        $plural = 0;

        if($request->user){
            if( !isset($request->invoice) ){
                $orders = Order::where('client', $request->client)
                        ->get();
                // $follow = Follow::where('user_id', auth()->user()->id)
                //         ->where('order_id', $order->id)
                //         ->first();
                $plural = 1;

                $order = NULL;
                $follow = NULL;
            }else{
                $order = Order::where('invoice', $request->invoice)
                            ->where('client', $request->client)
                            ->first();
                if( $order ){
                    $follow = Follow::where('user_id', auth()->user()->id)
                            ->where('order_id', $order->id)
                            ->first();
                }else{
                    $follow = 0;
                }
                // dd($order->id, auth()->user()->id, $follow);
            }

        }else{
            $order = Order::where('invoice', $request->invoice)->first();
            if( $order ){
                $follow = Follow::where('user_id', auth()->user()->id)
                        ->where('order_id', $order->id)
                        ->first();
            }else{
                $follow = 0;
            }
            // dd($order->id, auth()->user()->id, $follow);
        }


        return view('dashboard', compact('order', 'orders', 'users', 'logs', 'follow', 'plural'));
    }

    public function picture(Request $request)
    {
        // dd($request->all());
        $order = Order::find($request->order);

        return view('upload', compact('order'));
    }

}
