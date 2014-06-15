<?php
namespace TypiCMS\Modules\Contacts\Models;

use TypiCMS\Models\Base;
use TypiCMS\Presenters\PresentableTrait;

class Contact extends Base
{

    use PresentableTrait;

    protected $presenter = 'TypiCMS\Modules\Contacts\Presenters\ModulePresenter';

    protected $fillable = array(
        'title',
        'first_name',
        'last_name',
        'email',
        'language',
        'website',
        'company',
        'address',
        'postcode',
        'city',
        'country',
        'phone',
        'mobile',
        'fax',
        'message',
    );

    /**
     * The default route for admin side.
     *
     * @var string
     */
    public $route = 'contacts';

    /**
     * lists
     */
    public $order = 'created_at';
    public $direction = 'desc';
}
