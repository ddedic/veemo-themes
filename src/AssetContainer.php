<?php namespace Veemo\Themes;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Request;

/**
 * Class AssetContainer
 * @package Veemo\Themes
 */
class AssetContainer
{

    /**
     * Use a theme path.
     *
     * @var boolean
     */
    public $usePath = true;

    /**
     * Path to theme.
     *
     * @var string
     */
    public $path;

    /**
     * The asset container name.
     *
     * @var string
     */
    public $name;


    /**
     * Module name if we are using $this->module($name) chained method
     *
     * @var string
     */
    public $module = null;

    /**
     * Create a new asset container instance.
     *
     * @param  string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Root asset path.
     *
     * @param  string $uri
     * @param  boolean $secure
     * @return string
     */
    public function originUrl($uri, $secure = null)
    {
        return $this->configAssetUrl($uri, $secure);
    }

    /**
     * Generate a URL to an application asset.
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    protected function configAssetUrl($path, $secure = null)
    {
        static $assetUrl;


        // Remove this.
        $i = 'index.php';

        if (URL::isValidUrl($path)) return $path;

        // Finding asset url config.
        if (is_null($assetUrl)) {
            $assetUrl = \Config::get('veemo.themes.assetUrl', '');
        }

        // Using asset url, if available.
        if ($assetUrl) {
            $base = rtrim($assetUrl, '/');

            // Asset URL without index.
            $basePath = str_contains($base, $i) ? str_replace('/' . $i, '', $base) : $base;
        } else {
            if (is_null($secure)) {
                $scheme = Request::getScheme() . '://';
            } else {
                $scheme = $secure ? 'https://' : 'http://';
            }

            // Get root URL.
            $root = Request::root();
            $start = starts_with($root, 'http://') ? 'http://' : 'https://';
            $root = preg_replace('~' . $start . '~', $scheme, $root, 1);

            // Asset URL without index.
            $basePath = str_contains($root, $i) ? str_replace('/' . $i, '', $root) : $root;
        }


        return $basePath . '/' . $path;
    }

    /**
     * Return asset path with current theme path.
     *
     * @param  string $uri
     * @param  boolean $secure
     * @return string
     */
    public function url($uri, $secure = null)
    {
        // If path is full, so we just return.
        if (preg_match('#^http|//:#', $uri)) {
            return $uri;
        }

        $path = $this->getCurrentPath() . $uri;

        return $this->configAssetUrl($path, $secure);
    }

    /**
     * Get path from asset.
     *
     * @return string
     */
    public function getCurrentPath()
    {
        return Asset::$path;
    }

    /**
     * Return image with current theme/module path.
     *
     * @param  string $filename
     * @param  boolean $imgTag
     * @param  array $attributes
     * @param  boolean $secure
     * @return string
     */
    public function image($filename, $imgTag = false, $attributes = [], $secure = null)
    {
        $image_extensions_allowed = \Config::get('veemo.themes.allowedExtensions.images', '');
        $ext = \File::extension($filename);

        if (!in_array($ext, $image_extensions_allowed)) {
            return null;
        }

        // If path is full, so we just return.
        if (preg_match('#^http|//:#', $filename)) {
            return $filename;
        }


        // Prepend path to theme/module.
        if ($this->isUsePath()) {


            if ($this->module === null) {

                $path = $this->getCurrentPath() . $filename;

            } else {

                $path = $this->evaluatePath($this->getCurrentPath() . 'modules/' . $this->module . '/' . $filename);

                // Reset module name
                $this->module(null);

            }


        } else {
            $path = $filename;
            // Reset usePath() set to True (themes)
            $this->usePath(true);
        }


        $image = $this->configAssetUrl($path, $secure);


        if ($imgTag) {

            $image = '<img src="' . $image . '"' . $this->attributes($attributes) . ' />';

        }


        return $image;

    }

    /**
     * Check using theme path.
     *
     * @return boolean
     */
    public function isUsePath()
    {
        return (boolean)$this->usePath;
    }

    /**
     * Evaluate path to current theme or force use theme.
     *
     * @param  string $source
     * @return string
     */
    protected function evaluatePath($source)
    {
        static $theme;


        // Make theme to use few features.
        if (!$theme) {
            $theme = \App::make('veemo.theme');
        }

        // Switch path to another theme.
        if (!is_bool($this->usePath) and $theme->exists($this->usePath)) {
            $currentTheme = $theme->getThemeName();

            $source = str_replace($currentTheme, $this->usePath, $source);
        }


        return $source;
    }

    /**
     * @param null $name
     * @return $this
     */
    public function module($name = null)
    {
        if (app('veemo.modules')) {

            $this->module = $name;

        }

        return $this;
    }

    /**
     * Force use a theme path.
     *
     * @param  boolean $use
     * @return AssetContainer
     */
    public function usePath($use = true)
    {
        $this->usePath = $use;

        return $this;
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array $attributes
     * @return string
     */
    public function attributes($attributes)
    {
        $html = array();

        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        foreach ((array)$attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (!is_null($element)) $html[] = $element;
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param  string $key
     * @param  string $value
     * @return string
     */
    protected function attributeElement($key, $value)
    {
        if (is_numeric($key)) $key = $value;

        if (!is_null($value)) return $key . '="' . e($value) . '"';
    }

    /**
     * Alias add an asset to container.
     *
     * @param string $name
     * @param string $source
     * @param array $dependencies
     * @param array $attributes
     */
    public function add($name, $source, $dependencies = array(), $attributes = array())
    {
        $this->added($name, $source, $dependencies, $attributes);
    }

    /**
     * Add an asset to the container.
     *
     * The extension of the asset source will be used to determine the type of
     * asset being registered (CSS or JavaScript). When using a non-standard
     * extension, the style/script methods may be used to register assets.
     *
     * <code>
     *      // Add an asset to the container
     *      Asset::container()->add('jquery', 'js/jquery.js');
     *
     *      // Add an asset that has dependencies on other assets
     *      Asset::add('jquery', 'js/jquery.js', 'jquery-ui');
     *
     *      // Add an asset that should have attributes applied to its tags
     *      Asset::add('jquery', 'js/jquery.js', null, array('defer'));
     * </code>
     *
     * @param  string $name
     * @param  string $source
     * @param  array $dependencies
     * @param  array $attributes
     * @return AssetContainer
     */
    protected function added($name, $source, $dependencies = array(), $attributes = array())
    {

        // If path is full, so we just return.
        if (preg_match('#^http|//:#', $source)) {

            $type = (pathinfo($source, PATHINFO_EXTENSION) == 'css') ? 'style' : 'script';

            $this->register($type, $name, $source, $dependencies, $attributes);

            return $this;
        }


        if (is_array($source)) {
            foreach ($source as $path) {
                $name = $name . '-' . md5($path);

                $this->added($name, $path, $dependencies, $attributes);
            }
        } else {
            $type = (pathinfo($source, PATHINFO_EXTENSION) == 'css') ? 'style' : 'script';

            // Remove unnecessary slashes from internal path.
            if (!preg_match('|^//|', $source)) {
                $source = ltrim($source, '/');
            }

            return $this->$type($name, $source, $dependencies, $attributes);
        }
    }

    /**
     * Add an asset to the array of registered assets.
     *
     * @param  string $type
     * @param  string $name
     * @param  string $source
     * @param  array $dependencies
     * @param  array $attributes
     * @return void
     */
    protected function register($type, $name, $source, $dependencies, $attributes)
    {
        $dependencies = (array)$dependencies;

        $attributes = (array)$attributes;

        $this->assets[$type][$name] = compact('source', 'dependencies', 'attributes');
    }

    /**
     * Write a script to the container.
     *
     * @param  string $name
     * @param  string string
     * @param  string $source
     * @param  array $dependencies
     * @return AssetContainer
     */
    public function writeScript($name, $source, $dependencies = array())
    {
        $source = '<script>' . $source . '</script>';

        return $this->write($name, 'script', $source, $dependencies);
    }

    /**
     * Write a content to the container.
     *
     * @param  string $name
     * @param  string string
     * @param  string $source
     * @param  array $dependencies
     * @return AssetContainer
     */
    protected function write($name, $type, $source, $dependencies = array())
    {
        $types = array(
            'script' => 'script',
            'style' => 'style',
            'js' => 'script',
            'css' => 'style'
        );

        if (array_key_exists($type, $types)) {
            $type = $types[$type];

            $this->register($type, $name, $source, $dependencies, array());
        }

        return $this;
    }

    /**
     * Write a style to the container.
     *
     * @param  string $name
     * @param  string string
     * @param  string $source
     * @param  array $dependencies
     * @return AssetContainer
     */
    public function writeStyle($name, $source, $dependencies = array())
    {
        $source = '<style>' . $source . '</style>';

        return $this->write($name, 'style', $source, $dependencies);
    }

    /**
     * Write a content without tag wrapper.
     *
     * @param  string $name
     * @param  string string
     * @param  string $source
     * @param  array $dependencies
     * @return AssetContainer
     */
    public function writeContent($name, $source, $dependencies = array())
    {
        $source = $source;

        return $this->write($name, 'script', $source, $dependencies);
    }

    /**
     * Add a CSS file to the registered assets.
     *
     * @param  string $name
     * @param  string $source
     * @param  array $dependencies
     * @param  array $attributes
     * @return AssetContainer
     */
    public function style($name, $source, $dependencies = array(), $attributes = array())
    {
        if (!array_key_exists('media', $attributes)) {
            $attributes['media'] = 'all';
        }

        // Prepend path to theme.
        if ($this->isUsePath()) {
            if ($this->module !== null) {
                $source = $this->evaluatePath($this->getCurrentPath() . 'modules/' . $this->module . '/' . $source);
            } else {
                $source = $this->evaluatePath($this->getCurrentPath() . $source);
            }


            // Reset module name
            $this->module(null);
        }

        $this->register('style', $name, $source, $dependencies, $attributes);

        return $this;
    }

    /**
     * Add a JavaScript file to the registered assets.
     *
     * @param  string $name
     * @param  string $source
     * @param  array $dependencies
     * @param  array $attributes
     * @return AssetContainer
     */
    public function script($name, $source, $dependencies = array(), $attributes = array())
    {
        // Prepaend path to theme.
        if ($this->isUsePath()) {
            $source = $this->evaluatePath($this->getCurrentPath() . $source);

            // Reset module name
            $this->module(null);
        }

        $this->register('script', $name, $source, $dependencies, $attributes);

        return $this;
    }

    /**
     * Get the links to all of the registered CSS assets.
     *
     * @return  string
     */
    public function styles()
    {
        return $this->group('style');
    }

    /**
     * Get all of the registered assets for a given type / group.
     *
     * @param  string $group
     * @return string
     */
    protected function group($group)
    {
        if (!isset($this->assets[$group]) or count($this->assets[$group]) == 0) return '';

        $assets = '';

        foreach ($this->arrange($this->assets[$group]) as $name => $data) {
            $assets .= $this->asset($group, $name);
        }

        return $assets;
    }

    /**
     * Sort and retrieve assets based on their dependencies
     *
     * @param   array $assets
     * @return  array
     */
    protected function arrange($assets)
    {
        list($original, $sorted) = array($assets, array());

        while (count($assets) > 0) {
            foreach ($assets as $asset => $value) {
                $this->evaluateAsset($asset, $value, $original, $sorted, $assets);
            }
        }

        return $sorted;
    }

    /**
     * Evaluate an asset and its dependencies.
     *
     * @param  string $asset
     * @param  string $value
     * @param  array $original
     * @param  array $sorted
     * @param  array $assets
     * @return void
     */
    protected function evaluateAsset($asset, $value, $original, &$sorted, &$assets)
    {
        // If the asset has no more dependencies, we can add it to the sorted list
        // and remove it from the array of assets. Otherwise, we will not verify
        // the asset's dependencies and determine if they've been sorted.
        if (count($assets[$asset]['dependencies']) == 0) {
            $sorted[$asset] = $value;

            unset($assets[$asset]);
        } else {
            foreach ($assets[$asset]['dependencies'] as $key => $dependency) {
                if (!$this->dependecyIsValid($asset, $dependency, $original, $assets)) {
                    unset($assets[$asset]['dependencies'][$key]);

                    continue;
                }

                // If the dependency has not yet been added to the sorted list, we can not
                // remove it from this asset's array of dependencies. We'll try again on
                // the next trip through the loop.
                if (!isset($sorted[$dependency])) continue;

                unset($assets[$asset]['dependencies'][$key]);
            }
        }
    }

    /**
     * Verify that an asset's dependency is valid.
     * A dependency is considered valid if it exists, is not a circular reference, and is
     * not a reference to the owning asset itself. If the dependency doesn't exist, no
     * error or warning will be given. For the other cases, an exception is thrown.
     *
     * @param  string $asset
     * @param  string $dependency
     * @param  array $original
     * @param  array $assets
     *
     * @throws \Exception
     * @return bool
     */
    protected function dependecyIsValid($asset, $dependency, $original, $assets)
    {
        if (!isset($original[$dependency])) {
            return false;
        } elseif ($dependency === $asset) {
            throw new \Exception("Asset [$asset] is dependent on itself.");
        } elseif (isset($assets[$dependency]) and in_array($asset, $assets[$dependency]['dependencies'])) {
            throw new \Exception("Assets [$asset] and [$dependency] have a circular dependency.");
        }

        return true;
    }

    /**
     * Get the HTML link to a registered asset.
     *
     * @param  string $group
     * @param  string $name
     * @return string
     */
    protected function asset($group, $name)
    {
        if (!isset($this->assets[$group][$name])) return '';

        $asset = $this->assets[$group][$name];

        // If the bundle source is not a complete URL, we will go ahead and prepend
        // the bundle's asset path to the source provided with the asset. This will
        // ensure that we attach the correct path to the asset.
        if (filter_var($asset['source'], FILTER_VALIDATE_URL) === false) {
            $asset['source'] = $this->path($asset['source']);
        }

        // If source is not a path to asset, render without wrap a HTML.
        if (strpos($asset['source'], '<') !== false) {
            return $asset['source'];
        }

        // This line fixing config path.
        $asset['source'] = $this->configAssetUrl($asset['source']);

        //return HTML::$group($asset['source'], $asset['attributes']);
        return $this->html($group, $asset['source'], $asset['attributes']);
    }

    /**
     * Returns the full-path for an asset.
     *
     * @param  string $source
     * @return string
     */
    public function path($source)
    {
        return $source;
    }

    public function html($group, $source, $attributes)
    {
        switch ($group) {
            case 'script' :
                $attributes['src'] = $source;

                return '<script' . $this->attributes($attributes) . '></script>' . PHP_EOL;
            case 'style' :

                $defaults = array('media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet');

                $attributes = $attributes + $defaults;

                $attributes['href'] = $source;

                return '<link' . $this->attributes($attributes) . '>' . PHP_EOL;
        }
    }

    /**
     * Get the links to all of the registered JavaScript assets.
     *
     * @return  string
     */
    public function scripts()
    {
        return $this->group('script');
    }

}
