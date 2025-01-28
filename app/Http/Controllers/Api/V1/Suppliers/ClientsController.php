<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\Clients\StoreClientRequest;
use App\Http\Requests\Suppliers\Clients\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Interfaces\Suppliers\ClientRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientsController extends Controller
{
    private ClientRepositoryInterface $clientRepositoryInterface;

    public function __construct(ClientRepositoryInterface $clientRepositoryInterface)
    {
        $this->clientRepositoryInterface = $clientRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->clientRepositoryInterface->index();
        return ApiResponseClass::sendResponse(ClientResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        // prepare data to store it
        $data = [
            'image' => $request->file('image') ?? null,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'description' => $request->description,
            'password' => Hash::make('123456789'),
            'lng' => $request->lng,
            'lat' => $request->lat,
        ];

        // start DB transaction
        DB::beginTransaction();
        try {
            // store client data
            $client = $this->clientRepositoryInterface->store($data);
            // attach store to this supplier
            Auth::user()->stores()->attach($client->id);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(null, trans('clients.inserted', ['name' => $client->name]));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get client by id
        $client = $this->clientRepositoryInterface->getById($id);
        if ($client)
            return ApiResponseClass::sendResponse(new ClientResource($client));
        // return not found
        return ApiResponseClass::sendResponse(null, trans('global.not found'), false, 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, string $id)
    {
        // prepare data to store it
        $data = [
            'image' => $request->file('image') ?? null,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'description' => $request->description,
            'lng' => $request->lng,
            'lat' => $request->lat,
        ];

        // start DB transaction
        DB::beginTransaction();
        try {
            // store client data
            $client = $this->clientRepositoryInterface->update($data, $id);
            // commit DB transaction
            DB::commit();
            return ApiResponseClass::sendResponse(trans('global.updated'));
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->clientRepositoryInterface->delete($id);
        return ApiResponseClass::sendResponse(trans('global.deleted'));
    }
}
