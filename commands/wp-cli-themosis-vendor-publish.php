<?php
	
if ( !defined( 'WP_CLI' ) ) return;

use League\Flysystem\MountManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

class Themosis_Vendor_Command extends WP_CLI_Command
{
	
	/**
     * Execute the console command.
     *
     * @return void
     */
	public function publish($args, $options) {

		$this->files = new Filesystem();
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
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'vendor:publish {--force : Overwrite any existing files.}
                    {--provider= : The service provider that has assets you want to publish.}
                    {--tag=* : One or many tags that have assets you want to publish.}';
                    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish any publishable assets from vendor packages';
    
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
        if ($this->files->isFile($from)) {
            return $this->publishFile($from, $to);
        } elseif ($this->files->isDirectory($from)) {
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
        if (! $this->files->exists($to) || $this->getOption('force')) {
            $this->createParentDirectory(dirname($to));
            $this->files->copy($from, $to);
            $this->status($from, $to, 'File');
        }
    }
    
    /**
     * Publish the directory to the given directory.
     *
     * @param  string  $from
     * @param  string  $to
     * @return void
     */
    protected function publishDirectory($from, $to)
    {
        $this->moveManagedFiles(new MountManager([
            'from' => new Flysystem(new LocalAdapter($from)),
            'to' => new Flysystem(new LocalAdapter($to)),
        ]));
        $this->status($from, $to, 'Directory');
    }
    
    /**
     * Move all the files in the given MountManager.
     *
     * @param  \League\Flysystem\MountManager  $manager
     * @return void
     */
    protected function moveManagedFiles($manager)
    {
        foreach ($manager->listContents('from://', true) as $file) {
            if ($file['type'] === 'file' && (! $manager->has('to://'.$file['path']) || $this->getOption('force'))) {
                $manager->put('to://'.$file['path'], $manager->read('from://'.$file['path']));
            }
        }
    }
    
    /**
     * Create the directory to house the published files if needed.
     *
     * @param  string  $directory
     * @return void
     */
    protected function createParentDirectory($directory)
    {
        if (! $this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
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
        $from = str_replace(base_path(), '', realpath($from));
        $to = str_replace(base_path(), '', realpath($to));
        WP_CLI::success('<info>Copied '.$type.'</info> <comment>['.$from.']</comment> <info>To</info> <comment>['.$to.']</comment>');
    }
	
}

WP_CLI::add_command( 'kit vendor:publish', [new Themosis_Vendor_Command(), 'publish'] );