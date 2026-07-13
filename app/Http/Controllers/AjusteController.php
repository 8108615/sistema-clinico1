<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AjusteController extends Controller
{
    public function index()
    {
        $divisas = $this->getDivisas();
        $ajuste = Ajuste::query()->first();
        $selectedDivisa = null;

        if ($ajuste && isset($ajuste->divisa)) {
            foreach ($divisas as $codigo => $info) {
                if (isset($info['symbol']) && $info['symbol'] === $ajuste->divisa) {
                    $selectedDivisa = $codigo;
                    break;
                }
            }
        }
        return view('admin.ajustes.index', compact('divisas', 'ajuste', 'selectedDivisa'));
    }

    private function getDivisas()
    {
        // Carga el archivo desde la carpeta public
        $path = public_path('divisas.json');
        if (!file_exists($path)) {
            return [];
        }
        return json_decode(file_get_contents($path), true);
    }

    public function store(Request $request){

        //validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'divisa' => 'required|string|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'web' => 'nullable|string|max:255',

        ]);

        $ajusteExistente = Ajuste::first();

        if ($ajusteExistente) {

            //actualizar los datos
            $ajusteExistente->nombre = $request->nombre;
            $ajusteExistente->descripcion = $request->descripcion;
            $ajusteExistente->direccion = $request->direccion;
            $ajusteExistente->telefono = $request->telefono;
            $ajusteExistente->email = $request->email;
            $ajusteExistente->divisa = $request->divisa;

            if ($request->hasFile('logo')) {
                //eliminar el logo anterior si existe
                if ($ajusteExistente->logo) {
                    Storage::disk('public')->delete($ajusteExistente->logo);
                }
                $logoPath = $request->file('logo')->store('logos', 'public');
                $ajusteExistente->logo = $logoPath;
            }

            $ajusteExistente->web = $request->web;

            $ajusteExistente->save();

            return redirect()->route('admin.ajustes.index')
            ->with('mensaje', 'Ajustes guardados correctamente')
            ->with('icono', 'success');

        }else{

            //guardar los datos
            $ajuste = new Ajuste();
            $ajuste->nombre = $request->nombre;
            $ajuste->descripcion = $request->descripcion;
            $ajuste->direccion = $request->direccion;
            $ajuste->telefono = $request->telefono;
            $ajuste->email = $request->email;
            $ajuste->divisa = $request->divisa;

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $ajuste->logo = $logoPath;
            }

            $ajuste->web = $request->web;
            $ajuste->save();

            return redirect()->route('admin.ajustes.index')
            ->with('mensaje', 'Ajustes guardados correctamente')
            ->with('icono', 'success');
        }

    }

}
