<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medico;

class AdminController extends Controller
{

    public function confirmarExclusao($id)
    {
        $medico = Medico::findOrFail($id);
        
        return view('excluirMedico', compact('medico'));
    }

    public function excluir($id)
    {
        $medico = Medico::findOrFail($id);
        
        $medico->delete();

        return redirect()->route('admin.manutencaoMedicos')->with('success', 'Médico excluído com sucesso!');
    }
}
