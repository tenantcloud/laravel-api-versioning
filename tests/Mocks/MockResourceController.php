<?php

namespace Tests\Mocks;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class MockResourceController extends Controller
{
	public function index(Request $request): JsonResponse
	{
		return new JsonResponse(['route_name' => $request->route()->getName()]);
	}

	public function create(Request $request): JsonResponse
	{
		return new JsonResponse(['route_name' => $request->route()->getName()]);
	}

	public function store(Request $request): JsonResponse
	{
		return new JsonResponse(['route_name' => $request->route()->getName()], Response::HTTP_CREATED);
	}

	public function show(int $id, Request $request): JsonResponse
	{
		return new JsonResponse(['route_name' => $request->route()->getName()]);
	}

	public function edit(int $id, Request $request): JsonResponse
	{
		return new JsonResponse(['route_name' => $request->route()->getName()]);
	}

	public function update(int $id, Request $request): JsonResponse
	{
		return new JsonResponse(['route_name' => $request->route()->getName()]);
	}

	public function destroy(int $id, Request $request): JsonResponse
	{
		return new JsonResponse([], Response::HTTP_NO_CONTENT);
	}
}
