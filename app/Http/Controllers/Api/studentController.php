<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Students;

class studentController extends Controller
{
    public function index()
    {
        $students = Students::all();
        if($students->isEmpty()){
            return response()->json([
                "status" => "error",
                "message" => "No se encontraron estudiantes"
            ], 200);
        }
        return response()->json($students, 200);
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'language'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Datos incorrectos',
                'error' => $validator->errors()
            ], 400);
        }

        $student = Students::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language,
        ]);

        if(!$student){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear el estudiante'
            ], 500);
        }

        return response()->json($student, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Students::find($id);
        if(!$student){
            return response()->json([
                'status' => 'error',
                'message' => 'Estudiante no encontrado'
            ], 404);
        }
        return response()->json($student, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    // ... (inside class studentController extends Controller)

    public function update(Request $request, $id)
    {
        $student = Students::find($id);
        if(!$student){
            return response()->json([
                'status' => 'error',
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'language'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al validar los datos',
                'errors' => $validator->errors()
            ], 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;
        $student->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Estudiante actualizado con éxito',
            'data' => $student
        ], 200);
    }

    public function updatePart(Request $request, $id)
    {
        $student = Students::find($id);
        if(!$student){
            return response()->json([
                'status' => 'error',
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        // Definición de reglas de validación (opcional en este ejemplo parcial, 
        // pero se mantiene la estructura de la fuente)
        $validator = Validator::make($request->all(), [
            'name'=>'',
            'email'=>'email',
            'phone'=>'',
            'language'=>'',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al validar los datos',
                'errors' => $validator->errors()
            ], 400);
        }
        
        // Actualiza solo si el campo está presente en la solicitud (PATCH)
        if ($request->has('name')) {
            $student->name = $request->name;
        }
        if ($request->has('email')) {
            $student->email = $request->email;
        }
        if ($request->has('phone')) {
            $student->phone = $request->phone;
        }
        if ($request->has('language')) {
            $student->language = $request->language;
        }

        $student->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Estudiante actualizado parcialmente con éxito',
            'data' => $student
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Students::find($id);
        if(!$student){
            return response()->json([
                'status' => 'error',
                'message' => 'Estudiante no encontrado'
            ], 404);
        }
        $student->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Estudiante eliminado'
        ], 200);
    }
}
