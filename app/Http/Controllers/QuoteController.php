<?php

namespace FleetCart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteMail;

class QuoteController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
       
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_url'  => 'required|url',
            'phone'        => 'required|string|max:20',
            'email'        => 'required|email|max:255',
            'message'      => 'nullable|string',
        ]);

      
        Mail::to(setting('store_email'))->send(
            new QuoteMail([
                'product_name' => $request->product_name,
                'product_url'  => $request->product_url,
                'phone'        => $request->phone,
                'email'        => $request->email,
                'message'      => $request->message,
            ])
        );

      
        return response()->json([
            'success' => true,
            'message' => 'Quote request sent successfully 🔥',
        ]);
    }
}