<?php

namespace Modules\Admin\Sidebar;

use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Group;

class SidebarExtender extends BaseSidebarExtender
{
    public function extend(Menu $menu)
    {
        $menu->group(trans('admin::sidebar.content'), function (Group $group) {
            $group->weight(5);
            $group->hideHeading();

            $group->item(trans('admin::dashboard.dashboard'), function (Item $item) {
                $item->icon('fa fa-dashboard');
                $item->route('admin.dashboard.index');
                $item->isActiveWhen(route('admin.dashboard.index', null, false));
            });
        });

        $menu->group(trans('admin::sidebar.system'), function (Group $group) {
            $group->weight(10);

            
            $group->item('Revenue Share', function (Item $item) {
                $item->icon('fa fa-chart-line');
                $item->weight(20);
                $item->route('admin.revenue_share.index');
                $item->authorize(
            $this->auth->hasAccess('admin.revenue_share.index')
        );
    });

            $group->item(trans('admin::sidebar.appearance'), function (Item $item) {
                $item->icon('fa fa-paint-brush');
                $item->weight(15);
                $item->route('admin.sliders.index');
                $item->authorize(
                    $this->auth->hasAnyAccess(['admin.sliders.index', 'admin.storefront.edit'])
                );
            });
        });
    }
}
