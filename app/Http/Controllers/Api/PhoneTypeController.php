<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Http\Resources\PhoneTypeResource;
use App\Models\Contact;
use App\Models\PhoneType;

class PhoneTypeController extends Controller
{
    public function index()
    {
        return PhoneTypeResource::collection(PhoneType::all());
    }
}
