<?php
namespace Lavender\Services;

class ViewInjector
{
    /**
     * Inject views into layouts
     *
     * @param $section
     * @param array $config
     */
    public function append($section, array $config)
    {
        $config = array_merge([
            'content' => null,
            'config' => null,
            'layout' => null,
            'script' => null,
            'menu' => null,
            'style' => null,
            'workflow' => null,
            'meta' => null,
            'position' => 0
        ], $config);

        if($html = $this->renderByType($config)){

            view()->inject($section, $html . PHP_EOL . '@parent');

        }
    }

    /**
     * Render the layout html by type (see View/config/defaults.php)
     * @param $config
     * @return bool
     */
    protected function renderByType($config)
    {
        if($config['script']){

            return $this->script($config['script']);

        } elseif($config['meta']){

            return $this->meta($config['meta']);

        } elseif($config['style']){

            return $this->style($config['style']);

        } elseif($config['menu']){

            // todo allow application to implement renderers
            return menu($config['menu']);

        } elseif(workflow()->exists($config['workflow'])){

            return workflow($config['workflow']);

        } elseif(view()->exists($config['layout'])){

            //todo do not pre-render
            return view($config['layout'])->render();

        } elseif($config['config']){

            return config($config['config']);

        }

        return false;
    }

    /**
     * Generate a meta tag
     *
     * @param array $attributes
     * @return string
     */
    function meta($attributes = [])
    {
        return '<meta' . attr($attributes) . ' />' . PHP_EOL;
    }

    /**
     * Generate a link to a JavaScript file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     * @return string
     */
    public function script($url, $attributes = [], $secure = null)
    {
        $attributes['src'] = asset($url, $secure);

        return '<script'.attr($attributes).'></script>'.PHP_EOL;
    }

    /**
     * Generate a link to a CSS file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     * @return string
     */
    public function style($url, $attributes = [], $secure = null)
    {
        $defaults = ['media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet'];

        $attributes = $attributes + $defaults;

        $attributes['href'] = asset($url, $secure);

        return '<link'.attr($attributes).'>'.PHP_EOL;
    }
}