<?php
	
	namespace WPKit\Utils\Facades;
	
	use Illuminate\Support\Facades\Facade;
	use WPKit\Utils\HookManager;
	
	class Hook extends Facade {
		
	    /**
	     * Get the registered name of the component.
	     *
	     * @return string
	     */
	    protected static function getFacadeAccessor()
	    {
		    
	        return HookManager::class;
	        
	    }
	    
	}
