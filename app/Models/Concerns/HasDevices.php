<?php

namespace App\Models\Concerns;

use hisorange\BrowserDetect\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait HasDevices
{
    public function deviceLogin()
    {
        DB::table('devices')->updateOrInsert([
            'user_id' => auth()->user()->id,
            'agent' => request()->userAgent(),
            'ip_address' => request()->ip(),
        ], [
            'session_id' => request()->session()->getId(),
            'last_login' => now(),
        ]);
    }

    public function deviceLogout($request)
    {
        DB::table('devices')
            ->whereIn('id', auth()->user()->devices()->where('session_id', '!=', $request->session()->getId())->pluck('id'))
            ->delete();
    }

    public function devices()
    {
        return DB::table('devices')->where('user_id', $this->id)->get();
    }

    public function getDevices()
    {
        $sessions = DB::table('devices')->where('user_id', $this->id)->orderByDesc('last_login')->get();

        return collect($sessions)->map(function ($session) {
            return (object) [
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->session_id === request()->session()->getId(),
                'last_active' => Carbon::parse($session->last_login)->diffForHumans(),
            ];
        });
    }

    protected function createAgent($session)
    {
        $request = (new Request);
        $request->headers->set('User-Agent', $session->agent);

        return new Parser(null, $request, []);
    }
}
