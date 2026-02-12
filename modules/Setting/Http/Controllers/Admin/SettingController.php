<?php

namespace Modules\Setting\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Modules\Media\Entities\File;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Artisan;
use Modules\Admin\Ui\Facades\TabManager;
use Modules\Support\Services\PWAService;
use Illuminate\Contracts\Foundation\Application;
use Modules\Setting\Http\Requests\UpdateSettingRequest;

class SettingController
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function edit()
    {
        $settings = setting()->all();
        $tabs = TabManager::get('settings');

        return view('setting::admin.settings.edit', compact('settings', 'tabs'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSettingRequest $request
     * @param PWAService $PWAService
     *
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
  public function update(UpdateSettingRequest $request, PWAService $PWAService)
{
    //dd($request->all());
    $this->handleMaintenanceMode($request);

    if (setting('pwa_icon') !== request('pwa_icon')) {
        $file = File::find(request('pwa_icon'));
        $file && $PWAService->generateIcons($file);
        $PWAService->updatePWAVersionInServiceWorkerJs();
    }

    //setting($request->except('_token', '_method'));
   setting()->set('global_margin_percentage', $request->global_margin_percentage);
setting()->set('category_margin_enabled', $request->category_margin_enabled);
setting()->set('share_1', $request->share_1);
setting()->set('share_2', $request->share_2);
 return redirect()
    ->route('admin.dashboard.index')
    ->with('success', trans('setting::messages.settings_updated'));
}



    private function handleMaintenanceMode($request)
    {
        if ($request->maintenance_mode) {
            Artisan::call('down');
        } else if (app()->isDownForMaintenance()) {
            Artisan::call('up');
        }
    }
}
