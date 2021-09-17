<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiErrorCustom;
use App\Http\Controllers\Controller;
use App\Http\Requests\SimulatorRequest;
use App\Institutions;
use App\Simulator;
use App\Support\ReadFile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InstitutionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $readFile = new ReadFile("json/instituicoes.json");
            $data = $readFile->getDataFileCollection();
            return response()->json($data->all());
        } catch (\Exception $e) {
            throw new ApiErrorCustom($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Calculates the amount of fees per institution
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateFee(Request $request)
    {
        try {
            $convenants = new ReadFile("json/convenios.json");
            $instituitions = new ReadFile("json/instituicoes.json");
            $fees = new ReadFile("json/taxas_instituicoes.json");

            $rules = [
                'valor_emprestimo' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'instituicoes' => [
                    'required',
                    'sometimes',
                    'array',
                    Rule::in($instituitions->getDataFileCollectionByKey('chave')->toArray())
                ],
                'convenios' => [
                    'required',
                    'sometimes',
                    'array',
                    Rule::in($convenants->getDataFileCollectionByKey('chave')->toArray())
                ],
                'parcela' => [
                    'required',
                    'sometimes',
                    'array',
                    Rule::in($fees->getDataFileCollectionByKey('parcelas')->toArray())
                ]
            ];

            Institutions::validate($request, $rules);

            $simulation = new Simulator($fees->getDataFileCollection(), $request->all());
            $simulationData = $simulation->executeSimulation();

            return response()->json($simulationData);

        } catch (ApiErrorCustom $e) {
            throw new ApiErrorCustom($e->getMessage(), $e->getCode(), null, $e->getOptions());
        }
    }
}
