<?php

use App\Models\Ticket;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/tickets/{ticketNumber}', function ($ticketNumber) {
    // Query ke tabel tickets berdasarkan ticket_number
    $ticket = Ticket::where('ticket_number', $ticketNumber)
        ->first(['id', 'photo_url', 'status', 'issue', 'ticket_number', 'updated_at']);

    if ($ticket) {
        $parsedown = new \Parsedown();
        $ticket->issue = $parsedown->text($ticket->issue);
        $ticket->updated_at = Carbon::parse($ticket->updated_at)->format('d-m-Y H:i');

        // Ambil response terbaru menggunakan accessor
        $latestResponse = $ticket->latestResponse();
        
        // Format response dan updated_at jika ada response
        $response = $latestResponse ? [
            'response' => $latestResponse->response,
            'updated_at' => Carbon::parse($latestResponse->updated_at)->format('d-m-Y H:i'),
        ] : ['response' => 'Maaf Permasalahan Anda Belum Ditanggapi oleh Admin'];

        return response()->json([
            'ticket' => [
                'id' => $ticket->id,
                'photo_url' => $ticket->photo_url,
                'status' => $ticket->status,
                'issue' => $ticket->issue,
                'ticket_number' => $ticket->ticket_number,
                'updated_at' => $ticket->updated_at, // Sudah diformat
            ],
            'latest_response' => $response
        ]);
    } else {
        return response()->json(['message' => 'Ticket not found'], 404);
    }
});

Route::get('/psu', function (Request $request) {
    // Ambil data dari model Facility
    $facilities = Facility::select('name', 'length', 'width', 'lat', 'lng', 'photo_0', 'photo_100', 'construct_type', 'spending_type')
        ->get();

    // Ubah nilai null menjadi 'belum ada data'
    $facilities->transform(function ($item) {
        foreach ($item->getAttributes() as $key => $value) {
            if (is_null($value)) {
                $item[$key] = 'belum ada data';
            }
        }
        return $item;
    });

    return response()->json($facilities);
});