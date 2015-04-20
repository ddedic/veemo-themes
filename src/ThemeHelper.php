<?php
/**
 * Project: Veemo
 * User: ddedic
 * Email: dedic.d@gmail.com
 * Date: 12/04/15
 * Time: 23:07
 */


/**
 * Get the theme instance.
 *
 * @param  string $themeType
 * @param  string $themeName
 * @param  string $layoutName
 * @return \Veemo\Themes\Theme
 */
function theme($themeType = 'frontend', $themeName = null, $layoutName = null)
{
    $theme = app('veemo.theme');

    if ($themeName) {
        $theme->$themeType()->uses($themeName);
    }

    if ($layoutName) {
        $theme->layout($layoutName);
    }

    return $theme;
}


/**
 * @param string $filename
 * @param bool $imgTag
 * @param array $attributes
 * @param bool $secure
 * @return string
 */
function theme_image($filename = null, $imgTag = false, $attributes = [], $secure = false)
{

    if ($filename && $filename !== null) {
        $theme = app('veemo.theme');

        return $theme->asset()->image($filename, $imgTag, $attributes, $secure);
    }

    // @todo ENV detect
    app('log')->error('Method {theme_image} error. Filename parameter is not set.');

    return null;
}


/**
 * @param string $module
 * @param string $filename
 * @param bool $imgTag
 * @param array $attributes
 * @param bool $secure
 * @return string
 */
function module_image($module = null, $filename = null, $imgTag = false, $attributes = [], $secure = false)
{

    if ($module && $module !== null && $filename && $filename !== null) {
        $theme = app('veemo.theme');

        return $theme->asset()->module($module)->image($filename, $imgTag, $attributes, $secure);
    }

    // @todo ENV detect
    app('log')->error('Method {module_image} error. Module name or Filename parameter is not set.');


    return null;
}


/**
 * @param string $container
 * @return mixed
 */
function theme_styles($container = null)
{
    $theme = app('veemo.theme');

    if ($container !== null) {

        return $theme->asset()->container($container)->styles();

    }

    return $theme->asset()->styles();

}


/**
 * @param string $container
 * @return mixed
 */
function theme_scripts($container = null)
{
    $theme = app('veemo.theme');

    if ($container !== null) {

        return $theme->asset()->container($container)->scripts();

    }

    return $theme->asset()->scripts();

}


/**
 * @param null $view
 * @param array $args
 * @return mixed
 */
function theme_partial($view = null, $args = [])
{
    $theme = app('veemo.theme');

    if ($view !== null) {

        return $theme->partial($view, $args);

    }

    // @todo ENV detect
    app('log')->error('Method {module_partial} error. Filename/View parameter is not set.');

    return null;
}


/**
 * @return mixed
 */
function theme_content()
{
    $theme = app('veemo.theme');

    return $theme->content();
}