<?php
namespace TypiCMS\Services;

use DB;
use App;
use Route;
use Sentry;
use Config;
use Request;

class Helpers
{
    public function __construct()
    {
    }

    /**
     * I have slug, give me id.
     *
     * @param  string  $module
     * @param  string  $slug
     * @return integer
     */
    public static function getIdFromSlug($module = null, $slug = null)
    {
        if (! $module or ! $slug) {
            return false;
        }

        $moduleSingular = str_singular($module);
        $translationsTable = $moduleSingular . '_translations';

        return DB::table($module)
                ->join($translationsTable, $module . '.id', '=', $translationsTable . '.' . $moduleSingular . '_id')
                ->where('slug', $slug)
                ->where('locale', App::getLocale())
                ->pluck($module . '.id');
    }

    /**
     * I have id, give me slugs.
     *
     * @param  string $module
     * @param  int    $id
     * @return Array
     */
    public static function getSlugsFromId($module = null, $id = null)
    {
        if (! $module or ! $id) {
            return false;
        }

        $moduleSingular = str_singular($module);
        $translationsTable = $moduleSingular . '_translations';

        return DB::table($module)
                ->join($translationsTable, $module . '.id', '=', $translationsTable . '.' . $moduleSingular . '_id')
                ->where($module . '.id', $id)
                ->where($translationsTable . '.status', 1)
                ->lists('slug', 'locale');
    }
}
