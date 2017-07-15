<?php
		
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
    
	    function get_asset($file, $server_path = false) {
		    
		    return container('asset.finder')->find( $file );
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#THE ASSET FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('the_asset') ) {
    
	    function the_asset($file) {
	        
	        if( $asset = get_asset( $file ) ) {
		        
		        echo $asset;
		        
	        }
	        
	    }

	}

?>
