<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurnoCaja;
use App\Models\User;
use Modules\Sale\Entities\Sale;
use App\DataTables\TurnoCajaDataTable;
use Illuminate\Support\Facades\Gate;

use Yajra\DataTables\DataTables;




class TurnoCajaController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
            'usuario_id' => 'required|exists:users,id',
        ]);

        $turnoAbierto = TurnoCaja::where('usuario_id', $request->input('usuario_id'))->where('estado', 'abierto')->first();

        if ($turnoAbierto) {
            return redirect()->back()->with('error', 'Ya existe una apertura de caja para ese usuario.');
        }

        $turno = new TurnoCaja();
        $turno->monto_inicial = $request->input('monto_inicial');
        $turno->fecha_apertura = $request->input('fecha_apertura');
        $turno->usuario_id = $request->input('usuario_id');
        $turno->estado = 'abierto';
        $turno->save();

        return redirect()->route('turnos.index')->with('success', 'Se abrió el turno de caja correctamente.');
    }


    public function cerrar(Request $request, TurnoCaja $turno)
    {
        // Validaciones
        $request->validate([
            'monto_final' => 'required|numeric|min:0',
            'fecha_cierre' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Verifica si el turno de caja está abierto
        if ($turno->estado !== 'abierto') {
            return redirect()->route('turnos.index')->with('error', 'El turno de caja ya está cerrado.');
        }

        // Obtener información adicional para mostrar en la vista
        $usuarioConCajaAbierta = TurnoCaja::where('usuario_id', $turno->usuario_id)
            ->where('estado', 'abierto')
            ->first();

        $ventasTurno = Sale::where('turno_caja_id', $turno->id)->count();
        $totalVentasTurno = Sale::where('turno_caja_id', $turno->id)->sum('total_amount');

        // Actualiza el turno de caja con la fecha de cierre y el monto final
        $turno->update([
            'fecha_cierre' => $request->input('fecha_cierre'),
            'monto_final' => $request->input('monto_final'),
            'comentario' => $request->input('comentario'),
            'estado' => 'cerrado',
        ]);

        // Puedes realizar más acciones aquí, como generar reportes, calcular diferencias, etc.

        return view('turnos.cerrar', compact('turno', 'usuarioConCajaAbierta', 'ventasTurno', 'totalVentasTurno'))
        ->with('success', 'Se cerró el turno de caja correctamente.');
    }

    /**
     * Display a listing of the resource.

    public function index()
    {
      $turnos = TurnoCaja::with('usuario')
                         ->orderBy('fecha_apertura', 'DESC')
                         ->get();

      return view('turnos.index', compact('turnos'));
    } */

    public function index(Request $request, TurnoCajaDataTable $dataTable)
    {
       // abort_if(Gate::denies('access_expenses'), 403);

        abort_if(Gate::denies('access_expenses') && Gate::denies('delete_expenses'), 403);

        $cerradas = $request->get('cerradas');

        return $dataTable->setCerradas($cerradas)->render('turnos.index', compact('cerradas'));
    }

    public function showCerrar(Request $request, TurnoCaja $turno)
    {
        abort_if(Gate::denies('access_expenses') && Gate::denies('delete_expenses'), 403);

        // Verifica si el turno de caja está abierto
        if ($turno->estado !== 'abierto') {
            return redirect()->back()->with('error', 'El turno de caja no está abierto.');
        }

        // Obtiene el usuario con caja abierta
        $usuarioConCajaAbierta = TurnoCaja::where('usuario_id', $turno->usuario_id)
            ->where('estado', 'abierto')
            ->first();

        // Obtiene la cantidad de ventas realizadas durante el turno
        $ventasTurno = Sale::where('turno_caja_id', $turno->id)->count();

        // Obtiene la suma total de ventas durante el turno
        $totalVentasTurno = Sale::where('turno_caja_id', $turno->id)->sum('total_amount');

        // Convierte la suma total de ventas a formato decimal
        $totalVentasTurnoDecimal = number_format($totalVentasTurno / 100, 2, '.', '');

        // Obtener la suma del monto inicial y el total de ventas
        $montoInicial = $turno->monto_inicial;
        $montoTotal = $montoInicial + $totalVentasTurnoDecimal;

        return view('turnos.cerrar', compact('turno', 'usuarioConCajaAbierta', 'ventasTurno', 'totalVentasTurnoDecimal', 'montoInicial', 'montoTotal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        abort_if(Gate::denies('create_expenses'), 403);

        $usuarios = User::all();

        return view('turnos.create', compact( 'usuarios'));

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $turno = TurnoCaja::findOrFail($id);

        // Realizar cálculos similares a la función showCerrar
        $ventasTurno = Sale::where('turno_caja_id', $turno->id)->count();
        $totalVentasTurno = Sale::where('turno_caja_id', $turno->id)->sum('total_amount');
        $totalVentasTurnoDecimal = number_format($totalVentasTurno / 100, 2, '.', '');
        $montoInicial = $turno->monto_inicial;
        $montoTotal = $montoInicial + $totalVentasTurnoDecimal;

        // Obtener directamente el usuario asociado al turno
        $usuario = $turno->usuario;

        return view('turnos.ver_cierre', compact('turno', 'usuario', 'ventasTurno', 'totalVentasTurnoDecimal', 'montoInicial', 'montoTotal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuarios = User::all();
        return view('turnos.create', compact('usuarios'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
