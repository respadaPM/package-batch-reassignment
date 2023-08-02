<?php
namespace ProcessMaker\Package\PackageBatchReassignment\Http\Middleware;

use Closure;
use Lavary\Menu\Facade as Menu;


class AddToMenus
{

    public function handle($request, Closure $next)
    {

        // Add a menu option to the top to point to our page

        //$menu = Menu::get('topnav');
        //$menu->add(__('Skeleton'), ['route' => 'package.batch.reassignment.tab.index']);

        // Add a option in the requests menu to point to our page
        $menu = Menu::get('sidebar_request')->first();

        // Add our menu item to the sidebar
        $menu->add(__('Batch Reassignment   '), [
            'route' => 'package.batch.reassignment.index',
            'icon' => 'fa-list',
        ]);
        return $next($request);
    }

}
