<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ClienteRequest;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Http\Resources\Tenant\ClienteResource;
use App\Services\Tenant\ClienteService;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    private readonly ClienteService $service;

    public function __construct()
    {
        $this->service = new ClienteService();
    }

    public function findAll(FilterPaginateRequest $request)
    {
        $cliente = $this->service->findAll($request);

        return ClienteResource::collection($cliente);
    }

    public function findOne(mixed $id)
    {
        $cliente = $this->service->findOne($id);

        return ClienteResource::make($cliente);
    }

    public function create(Request $request)
    {
        $data = ClienteRequest::create($request->all())->validated();

        $cliente = $this->service->create($data);

        return ClienteResource::make($cliente);
    }
}
