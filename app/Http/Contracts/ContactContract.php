<?php
namespace App\Http\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ContactContract
{
    public function list()  : JsonResponse;

    public function create(Request $request)  : JsonResponse;

    public function show(int $id) : JsonResponse;

    public function update(int $id, Request $request) : JsonResponse;

    public function delete(int $id)  : JsonResponse;
}