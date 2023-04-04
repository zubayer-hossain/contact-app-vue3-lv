<?php

namespace App\Http\Controllers\Api;

use App\Http\Contracts\ContactContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public ContactContract $contactContract;

    public function __construct(ContactContract $contactContract) {
        $this->contactContract = $contactContract;
    }

    public function index(): JsonResponse
    {
        return $this->contactContract->list();
    }

    public function store(ContactRequest $request)
    {
        return $this->contactContract->create($request);
    }

    public function show(int $id)
    {
        return $this->contactContract->show($id);
    }

    public function update(ContactRequest $request, int $id)
    {
        return $this->contactContract->update($id, $request);
    }

    public function destroy(int $id)
    {
        return $this->contactContract->delete($id);
    }
}
