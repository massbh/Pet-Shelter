<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdoptionRequest;
use App\Models\Pet;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactConfirmation;

class ContactUsController {

    public function contactFormSubmit(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:5000'
            ]);

            // Prepare email data for all contact types
            $emailData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name ?? $validated['name']
            ];

            if ($validated['subject'] === 'adoption') {
                $adoptionData = $request->validate([
                    'visitDate' => 'required|date|after_or_equal:today',
                    'visitTime' => 'required|string',
                    'petInterest' => 'nullable|string|max:255'
                ]);
                
                //This searches the petID
                $petId = null;
                if (!empty($adoptionData['petInterest'])) {
                    $searchTerm = $adoptionData['petInterest'];
                    
                    // If the input is a number, searches petIDs first
                    if (is_numeric($searchTerm)) {
                        $pet = Pet::find($searchTerm); // Exact ID search
                    }
                    
                    // If it didn't find ID, search the name and return its ID
                    if (!isset($pet) || !$pet) {
                        $pet = Pet::where('name', 'LIKE', '%' . $searchTerm . '%')->first();
                    }
                    
                    if ($pet) {
                        $petId = $pet->id;
                    }
                }

                AdoptionRequest::create([
                    'user_id' => auth()->id(),
                    'pet_id' => $petId,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'visitDate' => $adoptionData['visitDate'],
                    'visitTime' => $adoptionData['visitTime'],
                    'message' => $validated['message'],
                    'status' => 'pending'
                ]);

                // Add adoption-specific data to email
                $emailData['visitDate'] = $adoptionData['visitDate'];
                $emailData['visitTime'] = $adoptionData['visitTime'];
                $emailData['petInterest'] = $adoptionData['petInterest'];

                // Send confirmation email for adoption
                Mail::to($validated['email'])->send(new ContactConfirmation($emailData));

                return response()->json([
                    'success' => true,
                    'message' => 'Your adoption request has been submitted! A confirmation email has been sent to your address.'
                ], 200);
            } else {

                //Here goes your implementation David.

                // Add optional fields if they exist
                if ($request->has('visitDate') && $request->visitDate) {
                    $emailData['visitDate'] = $request->visitDate;
                }
                if ($request->has('visitTime') && $request->visitTime) {
                    $emailData['visitTime'] = $request->visitTime;
                }
                if ($request->has('petInterest') && $request->petInterest) {
                    $emailData['petInterest'] = $request->petInterest;
                }

                // Send confirmation email for general contact
                Mail::to($validated['email'])->send(new ContactConfirmation($emailData));

                return response()->json([
                    'success' => true,
                    'message' => 'Your message has been sent successfully! A confirmation email has been sent to your address.'
                ], 200);

            }
        //Error catching:
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ], 500);
        }
    }

}