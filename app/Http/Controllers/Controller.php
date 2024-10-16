<?php

namespace App\Http\Controllers;

use App\Entities\Site\Settings;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;
}
