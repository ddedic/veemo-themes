<?php namespace Veemo\Themes\Adapters;

/**
 * Project: Veemo
 * User: ddedic
 * Email: dedic.d@gmail.com
 * Date: 16/03/15
 * Time: 23:42
 */

use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository;
use Illuminate\Support\Collection;

use Veemo\Core\Modules\Exceptions\FileMissingException;


/**
 * Class FileManagerAdapter
 * @package Veemo\Themes\Adapters
 */
class DummyManagerAdapter implements ThemeManagerAdapterInterface
{


    /**
     * @param $slug
     * @return bool
     */
    public function exist($slug)
    {
        // TODO: Implement exist() method.
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $themes = [
            'Default' => [
                'slug' => 'default',
                'type' => 'frontend',
                'enabled' => true
            ],

            'test' => [
                'slug' => 'default',
                'type' => 'frontend',
                'enabled' => true
            ],


            'AdminLTE' => [
                'slug' => 'default',
                'type' => 'backend',
                'enabled' => true
            ]
        ];

        return new Collection($themes);
    }

    /**
     * @param bool $enabled
     * @return mixed
     */
    public function getFrontend($enabled = true)
    {

        $themes = [
            'Default' => [
                'slug' => 'default',
                'type' => 'frontend',
                'enabled' => true
            ],

            'test' => [
                'slug' => 'test',
                'type' => 'frontend',
                'enabled' => true
            ]
        ];

        return new Collection($themes);

    }

    /**
     * @param bool $enabled
     * @return mixed
     */
    public function getBackend($enabled = true)
    {
        $themes = [

            'default' => [
                'slug' => 'default',
                'type' => 'backend',
                'enabled' => true
            ]
        ];

        return new Collection($themes);

    }

    /**
     * @param $slug
     * @return mixed
     */
    public function find($slug)
    {
        // TODO: Implement find() method.
    }

    /**
     * @return bool
     */
    public function isEnabled($slug)
    {
        // TODO: Implement isEnabled() method.
    }

    /**
     * @return bool
     */
    public function isDisabled($slug)
    {
        // TODO: Implement isDisabled() method.
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function enable($slug)
    {
        // TODO: Implement enable() method.
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function disable($slug)
    {
        // TODO: Implement disable() method.
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function setActive($slug)
    {
        // TODO: Implement setActive() method.
    }
}