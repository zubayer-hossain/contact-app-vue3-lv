<?php
namespace App\Http\Repositories;

use App\Http\Contracts\ContactContract;
use App\Models\Address;
use App\Models\Contact;
use App\Models\PhoneNumber;
use http\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactRepo implements ContactContract
{

    public function list(): JsonResponse
    {
        return successResponse(
            'Contacts has been fetched.',
            Contact::all()
        );
    }

    public function create(Request $request): JsonResponse
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

            return successResponse(
                'Contact has been created successfully.',
                $contact->with(['phoneNumber', 'address'])->first(),
            );
        } catch (Exception $e) {
            DB::rollback();

            return errorResponse(
                'Something went wrong'
            );
        }
    }

    public function show(int $id): JsonResponse
    {
        $contact = Contact::with(['phoneNumber', 'address'])->findOrFail($id);

        return successResponse(
            'Contact info has been fetched.',
            $contact
        );
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $contact = Contact::findOrFail($id);

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

            return successResponse(
                'Contact has been updated successfully.',
                $contact->with(['phoneNumber', 'address'])->first(),
            );
        } catch (\Exception $e) {
            DB::rollback();

            return errorResponse(
                'Something went wrong'
            );
        }
    }

    public function delete(int $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return successResponse(
            'Contact has been deleted successfully.'
        );
    }
}