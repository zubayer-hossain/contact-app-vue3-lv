<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Address;
use App\Models\Contact;
use App\Models\PhoneNumber;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
        return ContactResource::collection(Contact::all());
    }

    public function store(ContactRequest $request)
    {
        DB::beginTransaction();
        try {
            // Saving Contact
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Saving phone_numbers
            $phoneNumbers = [];
            foreach($request->phone_numbers as $item){
                $item['contact_id'] = $contact->id;
                $phoneNumbers[] = new PhoneNumber($item);
            }
            $contact->phoneNumber()->saveMany($phoneNumbers);

            // Saving addresses
            $addresses = [];
            foreach($request->addresses as $item){
                $item['contact_id'] = $contact->id;
                $addresses[] = new Address($item);
            }
            $contact->address()->saveMany($addresses);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Contact has been created successfully.',
                'data' => new ContactResource($contact),
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'success',
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function show(Contact $contact)
    {
        return new ContactResource($contact->with(['phoneNumber', 'address'])->first());
    }

    public function update(ContactRequest $request, Contact $contact)
    {
        dd($request->all(), $contact);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->noContent();
    }
}
