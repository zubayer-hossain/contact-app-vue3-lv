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
                'data' => new ContactResource($contact->with(['phoneNumber', 'address'])->first()),
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
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
        DB::beginTransaction();
        try {
            // Saving Contact
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->save();

            // Saving phone_numbers
            $ignorePhoneIds = [];
            foreach($request->phone_numbers as $item){
                if (isset($item['id'])) {
                    $phoneNumber = PhoneNumber::find($item['id']);
                    $phoneNumber->phone_type_id = $item['phone_type_id'];
                    $phoneNumber->phone_number = $item['phone_number'];
                    $phoneNumber->save();
                }else{
                    $phoneNumber =  new PhoneNumber();
                    $phoneNumber->contact_id = $contact->id;
                    $phoneNumber->phone_type_id = $item['phone_type_id'];
                    $phoneNumber->phone_number = $item['phone_number'];
                    $phoneNumber->save();
                }

                $ignorePhoneIds[] = $phoneNumber->id;
            }

            PhoneNumber::where('contact_id', $contact->id)
                ->whereNotIn('id', $ignorePhoneIds)
                ->delete();

            // Saving addresses
            $ignoreAddressIds = [];
            foreach($request->addresses as $item){
                if (isset($item['id'])) {
                    $address = Address::find($item['id']);
                    $address->address_line = $item['address_line'];
                    $address->pincode = $item['pincode'];
                    $address->save();
                }else{
                    $address = new Address();
                    $address->contact_id = $contact->id;
                    $address->address_line = $item['address_line'];
                    $address->pincode = $item['pincode'];
                    $address->save();
                }

                $ignoreAddressIds[] = $address->id;
            }

            Address::where('contact_id', $contact->id)
                ->whereNotIn('id', $ignoreAddressIds)
                ->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Contact has been updated successfully.',
                'data' => new ContactResource($contact->with(['phoneNumber', 'address'])->first()),
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'message2' => $e->getMessage(),
            ]);
        }
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->noContent();
    }
}
