<?php

	
	use Illuminate\Support\Facades\Input;

	if( ! defined( 'DS' ) ) {
		
		/**
	     * Define DS path for general use
	     *
	     * @var string
	     */
		define( 'DS', DIRECTORY_SEPARATOR );
		
	}
	
	/*----------------------------------------------*\
    	#SERVICE WRAPPERS
    \*----------------------------------------------*/

	if ( ! function_exists('session') ) {
		
	    /**
	     * Get / set the specified session value.
	     *
	     * If an array is passed as the key, we will assume you want to set an array of values.
	     *
	     * @param  array|string  $key
	     * @param  mixed  $default
	     * @return mixed
	     */
	    function session($key = null, $default = null)
	    {
	        if (is_null($key)) {
	            return app('session');
	        }
	        if (is_array($key)) {
	            return app('session')->put($key);
	        }
	        return app('session')->get($key, $default);
	    }
	    
	}

	if ( ! function_exists('view') ) {
		
	    /**
	     * Helper function to build views.
	     *
	     * @param string $view      The view relative path, name.
	     * @param array  $data      Passed data.
	     * @param array  $mergeData
	     *
	     * @return string
	     */
	    function view($view = null, array $data = [], array $mergeData = [])
	    {
	        $factory = app('view');
	        if (func_num_args() === 0) {
	            return $factory;
	        }
	        return $factory->make($view, $data, $mergeData)->render();
	    }
	    
	}
	
	/*----------------------------------------------*\
    	#PATHS
    \*----------------------------------------------*/

	if( ! function_exists('resources_path') ) {
		
		/**
	     * Gets the resources path
	     *
	     * @return string
	     */
	    function resources_path($path = '', $file = '')
	    {
		    if( function_exists('themosis_path') ) {
			    $root = themosis_path('theme.resources');
		    } else {
			    $root = get_stylesheet_directory() . DS . 'resources' . DS;
		    }
		    return $root . ( $path ? $path : '' ) . ( $file ? DS . ltrim( $file, DS ) : '' );
	    }
	    
	}
	
	if( ! function_exists('storage_path') ) {
		
		/**
	     * Gets the storage path
	     *
	     * @return string
	     */
	     
	    function storage_path($file = '')
	    {
		    if( function_exists('themosis_path') ) {
			    return themosis_path('storage') . ltrim( ( $file ? DS . $file : '' ), DS );
		    } else {
			    return resources_path('storage', $file);
		    }
	    }
	    
	}
	
	if( ! function_exists('config_path') ) {
		
		/**
	     * Gets the storage path
	     *
	     * @return string
	     */
	    function config_path($file = '')
	    {
		    return resources_path('config', $file);
	    }
	    
	}
	
	if( ! function_exists('view_path') ) {
		
		/**
	     * Gets the view path
	     *
	     * @return string
	     */
	    function view_path($file = '')
	    {
		    return resources_path('views', $file);
	    }
	    
	}
		
	if( ! function_exists( 'shortcode_path' ) ) {
		
		/**
	     * Gets the shortcodes path
	     *
	     * @return string
	     */
		function shortcode_path( $file = '' ) 
		{
			return resources_path('shortcodes', $file);
		}
		
	}
	
	if( ! function_exists( 'posttype_path' ) ) {
		
		/**
	     * Gets the posttypes path
	     *
	     * @return string
	     */
		function posttype_path( $file = '' ) 
		{
			return resources_path('posttypes', $file);
		}
		
	}
	
	if( ! function_exists( 'taxonomy_path' ) ) {
		
		/**
	     * Gets the taxonomies path
	     *
	     * @return string
	     */
		function taxonomy_path( $file = '' ) 
		{
			return resources_path('taxonomies', $file);
		}
		
	}

	/*----------------------------------------------*\
		#IS WP_LOGIN
    \*----------------------------------------------*/

    if ( ! function_exists('is_wp_login') ) {

		/**
	     * Is current url wp login page
	     *
	     * @return boolean
	     */
	    function is_wp_login() {

			return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php', 'wp-activate.php', 'wp-signup.php' ) );

	    }

	}
	
	/*----------------------------------------------*\
    	#ROUTES
    \*----------------------------------------------*/
	
	if( ! function_exists('get_current_url') ) {
		
		/**
	     * Get current url from request
	     *
	     * @return string
	     */
		function get_current_url() {
			
			return get_home_url( 1, Input::getRequestUri() );
			
		}
		
	}
	
	if( ! function_exists('get_current_url_path') ) {
		
		/**
	     * Get current url path from request
	     *
	     * @return string
	     */
		function get_current_url_path() {
			
			return rtrim( explode('?', get_current_url())[0], '/');
			
		}
		
	}
	
	if ( ! function_exists('is_route') ) {
    
    	/**
	     * Compare current route to route from string
	     *
	     * @return boolean
	     */
	    function is_route( $path ) {
		    
		    if( strpos( $path, '*' ) !== false ) {
			    
			    $is_route = strpos( get_current_url_path(), home_url( str_replace( '*', '', $path ) ) ) !== false;
			    
		    } else {
		    
		    	$is_route = home_url( $path ) == get_current_url_path();
		    	
		    }
			return $is_route;
		    
	    }
	    
	}
		
	/*----------------------------------------------*\
		#OUTPUT SVG ICON CODE
	\*----------------------------------------------*/
	
	if ( ! function_exists('icon') ) {
	
		/**
	     * Get an svg html element from a string
	     *
	     * @return string/void
	     */
		function icon($name, $css = '', $echo = true) {
			
			$icon = "<svg " . ( $css ? "class='$css'" : '' ) . "><use xlink:href='" . get_asset( "/images/icons.svg" ) . "#icon-$name'></use></svg>";
	    
			if( $echo ) {
				
				echo $icon;
				
			} else {
				
	        	return $icon;
	        	
	        }
	    
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#NICE VAR DUMP
    \*----------------------------------------------*/
    
    if ( ! function_exists('nice') ) {
     
     	/**
	     * Retrive nice json format from string
	     *
	     * @return string/void
	     */
	    function nice($content, $echo = true) {
		    
		    ob_start();
		    
	        echo '<pre class="var-dump">';
	        
	        print_r($content);
	        
	        echo '</pre>';
	        
	        $html = ob_get_contents();
	        
	        ob_end_clean();
	        
	        if( $echo ) {
		        
		        echo $html;
		        
	        } else {
		        
		        return $html;
		        
	        }
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#GET ASSET FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('get_asset') ) {
    
    	/**
	     * Get an asset path from string
	     *
	     * @return string
	     */
	    function get_asset( $file ) {
		    
		    if( app()->bound('asset.finder') ) {
		    
		    	return app('asset.finder')->find( $file );
		    	
		    } else {
			    
			    if( file_exists( get_stylesheet_directory() . DS . $file ) ) {
	                
	                return get_stylesheet_directory_uri() . DS . $file;
	                
	            }
			    
		    }
		    
		    return null;
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#THE ASSET FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('the_asset') ) {
    
    	/**
	     * Output an asset path from string
	     *
	     * @return void
	     */
	    function the_asset( $file ) {
	        
	        if( $asset = get_asset( $file ) ) {
		        
		        echo $asset;
		        
	        }
	        
	    }

	}

	/*----------------------------------------------*\
    	#HOOK FUNCTIONS
    \*----------------------------------------------*/

	if ( ! function_exists('action') ) {
	
		function action() {
			
			call_user_func_array('WPKit\Utils\Facades\Hook::' . __FUNCTION__, func_get_args());	
				
		}
		
	}
	
	if ( ! function_exists('filter') ) {
	
		function filter() {
			
			call_user_func_array('WPKit\Utils\Facades\Hook::' . __FUNCTION__, func_get_args());		
			
		}
		
	}


?>
