<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PushController extends Controller
{
    /**
     * Store the PushSubscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = auth()->user();
        $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true], 200);
    }

    /**
     * Delete push subscription
     */
    public function destroy()
    {
        $id = auth()->id();

        $rows = DB::table(config('webpush.table_name'))
            ->where('subscribable_type', 'App\Models\User')
            ->where('subscribable_id', $id)
            ->delete();

        return redirect()
            ->back()
            ->with('message', 'All (' .$rows . ') subscriptions deleted');
    }

    /**
     *
     */
    public function deleteEndpoint(Request $request)
    {
        $endpoint = $request->endpoint;
        $user = auth()->user();

        $user->deletePushSubscription($endpoint);

        return redirect()
            ->back()
            ->with('message', 'Subscription deleted');
    }
}
