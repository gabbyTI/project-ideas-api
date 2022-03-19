<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

/**
 * @group Profile Management
 */
class MeController extends Controller
{
    public function getMe(Request $request)
    {
        if (auth('sanctum')->check()) {
            return ApiResponder::meEndpointResponse(new UserResource(auth('sanctum')->user()));
        }

        return ApiResponder::meEndpointResponse(null);
    }
}
