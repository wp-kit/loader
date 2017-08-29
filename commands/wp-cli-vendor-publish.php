<?php
	
if ( !defined( 'WP_CLI' ) ) return;

use Illuminate\Support\ServiceProvider;

class WPKit_Vendor_Publish extends WP_CLI_Command
{
	
	/**
     * Execute the console command.
     *
     * @return void
     */
	public function publish($args, $options) {

		$this->options = $options;
		$tags = $this->getOption('tag') ?: [null];
		$tags = is_array($tags) ? $tags : [$tags];
		
        foreach ((array) $tags as $tag) {
            $this->publishTag($tag);
        }
		
	}
	
	/**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    
    /**
     * Array of options
     *
     * @var array
     */
    protected $options;   
    
    /**
     * Get an options
     *
     * @param  string  $option
     * @return string
     */
    protected function getOption($option)
    {
        return ! empty( $this->options[$option] ) ? $this->options[$option] : null;
    }
    
    /**
     * Publishes the assets for a tag.
     *
     * @param  string  $tag
     * @return mixed
     */
    protected function publishTag($tag)
    {
        foreach ($this->pathsToPublish($tag) as $from => $to) {
            $this->publishItem($from, $to);
        }
        WP_CLI::success('Publishing complete.');
    }
    
    /**
     * Get all of the paths to publish.
     *
     * @param  string  $tag
     * @return array
     */
    protected function pathsToPublish($tag)
    {
        return ServiceProvider::pathsToPublish(
            $this->getOption('provider'), $tag
        );
    }
    
    /**
     * Publish the given item from and to the given location.
     *
     * @param  string  $from
     * @param  string  $to
     * @return void
     */
    protected function publishItem($from, $to)
    {
        if (is_file($from)) {
            return $this->publishFile($from, $to);
        } elseif (is_dir($from)) {
            return $this->publishDirectory($from, $to);
        }
        WP_CLI::error("Can't locate path: <{$from}>");
    }
    
    /**
     * Publish the file to the given path.
     *
     * @param  string  $from
     * @param  string  $to
     * @return void
     */
    protected function publishFile($from, $to)
    {
        if (! file_exists($to) || $this->getOption('force')) {
            $this->createParentDirectory(dirname($to));
            copy($from, $to);
            $this->status($from, $to, 'File');
        }
    }
    
    /**
     * Publish the directory to the given directory.
     *
     * @param  string  $src
     * @param  string  $dst
     * @return void
     */
    protected function publishDirectory($src, $dst)
    {
        $dir = opendir($src); 
	    @mkdir($dst); 
	    while(false !== ( $file = readdir($dir)) ) { 
	        if (( $file != '.' ) && ( $file != '..' )) { 
	            if ( is_dir($src . '/' . $file) ) { 
	                $this->publishDirectory($src . '/' . $file,$dst . '/' . $file); 
	            } 
	            else { 
	                copy($src . '/' . $file,$dst . '/' . $file); 
	            } 
	        } 
	    } 
	    closedir($dir); 
        $this->status($from, $to, 'Directory');
    }
    
    /**
     * Create the directory to house the published files if needed.
     *
     * @param  string  $directory
     * @return void
     */
    protected function createParentDirectory($directory)
    {
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
    
    /**
     * Write a status message to the console.
     *
     * @param  string  $from
     * @param  string  $to
     * @param  string  $type
     * @return void
     */
    protected function status($from, $to, $type)
    {
        WP_CLI::success('Copied '.$type.' from ['.$from.'] to ['.$to.']');
    }
	
}

WP_CLI::add_command( 'kit vendor:publish', [new WPKit_Vendor_Publish(), 'publish'], array(
    'shortdesc' => 'Publish any publishable assets from vendor packages.',
    'synopsis' => array(
        array(
            'type'     => 'assoc',
            'name'     => 'tag',
            'optional' => true,
            'default'  => null,
            'description' => 'One or many tags that have assets you want to publish.'
        ),
        array(
            'type'     => 'assoc',
            'name'     => 'provider',
            'optional' => true,
            'default'  => null,
            'description' => 'The service provider that has assets you want to publish.'
        ),
        array(
            'type'     => 'assoc',
            'name'     => 'force',
            'optional' => true,
            'default'  => false,
            'description' => 'Overwrite any existing files.'
        )
    )
) );