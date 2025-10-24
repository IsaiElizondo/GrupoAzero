<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Ruta;
use App\Models\Cliente;
use App\Models\DireccionCliente;

class Order extends Model
{
    protected $fillable = [
        'office',
        'invoice',
        'invoice_number',
        'invoice_document',
        'client',
        'client_id',
        'client_direccion_id',
        'nombre_cliente',
        'direccion',
        'ciudad',
        'estado',
        'codigo_postal',
        'celular',
        'nombre_recibe',
        'credit',
        'status_id',
        'delete',
        'origin',
        'embarques_by',
        'end_at',
        'created_by'
    ];
    
    public static function countAll()
    {
        $list = DB::select("SELECT COUNT(*) AS tot FROM orders");
        $list = json_decode(json_encode($list), true);
        return $list[0]['tot'];
    }
    


    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function partials()
    {
        return $this->hasMany(Partial::class);
    }

    public function follow()
    {
        return $this->hasOne(Follow::class);
    }

    public function cancelation()
    {
        return $this->hasOne(Cancelation::class);
    }

    public function rebilling()
    {
        return $this->hasOne(Rebilling::class);
    }

    public function debolution()
    {
        return $this->hasOne(Debolution::class);
    }
    public function debolutions()
    {
        return $this->hasMany(Debolution::class);
    }

    public function purchaseorder()
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    public function manufacturingorder()
    {
        return $this->hasOne(ManufacturingOrder::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function quote()
    {
        return Quote::where("order_id",$this->id)->first(); 
    }

    public function embarques_office() : string {
        $usr = \App\User::where("id", $this->embarques_by)->first();
        return !empty($usr) ? $usr->office : "";
    }

    public function devolucionesParciales(){

        return $this->hasMany(\App\DevolucionParcial::class, 'order_id');

    }

    public function rutas(){
        return $this->belongsToMany(Ruta::class, 'ruta_pedido', 'order_id', 'ruta_id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function direccion(){
        return $this->belongsTo(DireccionCliente::class, 'cliente_direccion_id');
    }


}
