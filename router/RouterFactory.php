<?php

use Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		//$router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
		//$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
        
        
        $router[] = new Route('index.php', 'Admin:Default:default', Route::ONE_WAY);
    
 
        
    
        // ADMIN ROUTES
        $router[] = $adminRouter = new RouteList('Admin');

        $adminRouter[] = new Route("admin/<presenter>/<action>[/<id>]","Default:default");
    
        // FRONT ROUTES
        $router[] = $frontRouter = new RouteList('Front');  
        
        $frontRouter[] = new Route("<presenter>/<action>[/<id>]","Default:default");
        
		return $router;
	}

}
