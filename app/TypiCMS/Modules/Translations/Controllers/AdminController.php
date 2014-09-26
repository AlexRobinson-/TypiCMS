<?php
namespace TypiCMS\Modules\Translations\Controllers;

use Illuminate\Support\Collection;
use TypiCMS\Controllers\BaseAdminController;
use TypiCMS\Modules\Translations\Repositories\TranslationInterface;
use TypiCMS\Modules\Translations\Services\Form\TranslationForm;
use View;

class AdminController extends BaseAdminController
{

    public function __construct(TranslationInterface $translation, TranslationForm $translationform)
    {
        parent::__construct($translation, $translationform);
        $this->title['parent'] = trans_choice('translations::global.translations', 2);
    }

    /**
     * List models
     * GET /admin/model
     */
    public function index($parent = null)
    {
        $models = Collection::make($this->repository->getAll([], true));

        $this->layout->content = View::make('translations.admin.index')
            ->withModels($models);
    }
}
