<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;
//use WithPagination;


class ProductoComponent extends Component
{
    public function render()
    {
    	/*$productos = Producto::orderBy('id', 'desc')->paginate(10);
        return view('livewire.producto-component', compact('productos'));*/
    }
}
