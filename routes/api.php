<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MeetingController;


Route::get('/', function(){
    return response()->json(["Message"=>"Hello"]);
});


Route::prefix('/')->group(function () {
    Route::get('/meetings', [MeetingController::class, 'index'])->name('api.meetings.index');
    Route::get('/meetings/{roomId}/join', [MeetingController::class, 'join'])->name('api.meetings.join');
    Route::post('/meetings/{roomId}/ended', [MeetingController::class, 'ended'])->name('api.meetings.ended');
});
