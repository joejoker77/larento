<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Entities\Site\Settings;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(): View
    {
        $settings = Settings::all()->first();
        $phones = $settings ? implode("\r\n", $settings->phones) : '';
        $emails = $settings ? implode("\r\n", $settings->emails) : '';
        return view('admin.home', compact('settings', 'phones', 'emails'));
    }

    public function update(Request $request)
    {
        if (!$settings = Settings::all()->first()) {
            $settings = Settings::make(array_filter([
                'settings' => 'settings',
                'name' => $request->get('name') ?? null,
                'slogan' => $request->get('slogan') ?? null,
                'default_pagination' => $request->get('default_pagination') ?? null,
                'work_time' => $request->get('work_time') ?? null,
                'address' => $request->get('address') ?? null,
                'main_text' => $request->get('main_text') ?? null,
                'main_head' => $request->get('main_head') ?? null,
                'quantity_offer' => $request->get('quantity_offer') ?? null,
                'emails' => array_map('trim', preg_split('#[\r\n]+#', $request['emails'])) ?? null,
                'phones' => array_map('trim', preg_split('#[\r\n]+#', $request['phones'])) ?? null
            ]));
        } else {
            $settings->update([
                'settings' => 'settings',
                'name' => $request->get('name') ?? null,
                'slogan' => $request->get('slogan') ?? null,
                'default_pagination' => $request->get('default_pagination') ?? null,
                'work_time' => $request->get('work_time') ?? null,
                'address' => $request->get('address') ?? null,
                'main_text' => $request->get('main_text') ?? null,
                'main_head' => $request->get('main_head') ?? null,
                'quantity_offer' => $request->get('quantity_offer') ?? null,
                'emails' => array_map('trim', preg_split('#[\r\n]+#', $request['emails'])) ?? null,
                'phones' => array_map('trim', preg_split('#[\r\n]+#', $request['phones'])) ?? null
            ]);
        }
        $settings->save();

        return back()->with(['success' => 'Настройки сохранены'], compact('settings'));
    }
}
