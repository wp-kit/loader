<?php

	use WPKit\Foundation\Application;
	use Illuminate\Support\Facades\Input;

	if( ! defined( 'DS' ) ) {
		
		define( 'DS', DIRECTORY_SEPARATOR );
		
	}
	
	/*----------------------------------------------*\
    	#SERVICE WRAPPERS
    \*----------------------------------------------*/

	if ( ! function_exists('app') ) {
		
	    /**
	     * Helper function to quickly retrieve an instance.
	     *
	     * @param null  $abstract   The abstract instance name.
	     * @param array $parameters
	     *
	     * @return mixed
	     */
	    function app($abstract = null, array $parameters = [])
	    {
	        if (is_null($abstract)) {
	            return Application::getInstance();
	        }
	        return Application::getInstance()->make($abstract, $parameters);
	    }
	    
	}

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
			    $root = get_stylesheet_directory() . DS . 'resources';
		    }
		    return $root . ( $path ? DS . $path : '' ) . ltrim( ( $file ? DS . $file : '' ), DS );
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

	/*----------------------------------------------*\
		#IS WP_LOGIN
    \*----------------------------------------------*/

    if ( ! function_exists('is_wp_login') ) {

	    function is_wp_login() {

		return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php', 'wp-activate.php', 'wp-signup.php' ) );

	    }

	}
	
	/*----------------------------------------------*\
    	#ROUTES
    \*----------------------------------------------*/
	
	if( ! function_exists('get_current_url') ) {
		
		function get_current_url() {
			
			return get_home_url( 1, Input::getRequestUri() );
			
		}
		
	}
	
	if( ! function_exists('get_current_url_path') ) {
		
		function get_current_url_path() {
			
			return rtrim( explode('?', get_current_url())[0], '/');
			
		}
		
	}
	
	if ( ! function_exists('is_route') ) {
    
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
    
	    function get_asset( $file ) {
		    
		    if( app()->bound('asset.finder') ) {
		    
		    	return app('asset.finder')->find( $file );
		    	
		    } else {
			    
			    if( file_exists( get_stylesheet_directory() . DS . $dir . DS . $file ) ) {
	                
	                return get_stylesheet_directory_uri() . DS . $dir . DS . $file;
	                
	            }
			    
		    }
		    
		    return null;
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#THE ASSET FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('the_asset') ) {
    
	    function the_asset( $file ) {
	        
	        if( $asset = get_asset( $file ) ) {
		        
		        echo $asset;
		        
	        }
	        
	    }

	}

?>
