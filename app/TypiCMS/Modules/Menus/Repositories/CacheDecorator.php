<?php
namespace TypiCMS\Modules\Menus\Repositories;

use TypiCMS\Repositories\CacheAbstractDecorator;
use TypiCMS\Services\Cache\CacheInterface;

class CacheDecorator extends CacheAbstractDecorator implements MenuInterface
{

    // Class expects a repo and a cache interface
    public function __construct(MenuInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }

    /**
     * Build a menu
     * 
     * @param  string $name       menu name
     * @param  array  $attributes html attributes
     * @return string             html code of a menu
     */
    public function build($name, $attributes = array())
    {
        return $this->repo->build($name, $attributes);
    }
}
