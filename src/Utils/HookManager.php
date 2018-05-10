<?php

	namespace WPKit\Utils;
	
	class HookManager {
		
		public function action() {
			
			call_user_func_array(__NAMESPACE__ .'\hook', array_merge([__FUNCTION__], func_get_args()));	
				
		}
		
		public function filter() {
			
			call_user_func_array(__NAMESPACE__ .'\hook', array_merge([__FUNCTION__], func_get_args()));		
			
		}
		
		public function hook( $type, $hook, $callback ) {
			
			$trace = debug_backtrace();
			
			if( ! is_callable( $callback ) ) {
				
				foreach(range(1, 3) as $i) {
					
					if( ! empty( $trace[$i]['object'] ) ) {
						
						$caller = $trace[$i]['object'];
						
						if( method_exists($caller, $callback) ) {
							
							$callback = [$caller, $callback];
							
							break;
							
						}
						
					}
					
				}
				
			}
			
			$fn = "add_$type";
			
			if( function_exists( $fn ) ) {
				
				$args = func_get_args();
				
				unset($args[0]);
				
				call_user_func_array( $fn, $args );
				
			}
			
		}
		
	}
