<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

    /**
     * @OA\Info(
     *      version="v1",
     *      title="Project Ideas API Documentation",
     *      description="A documentation of exposed endpoints for the application",
     *      @OA\Contact(
     *          email="gabrielibenye@gmail.com"
     *      ),
     *      @OA\License(
     *          name="The MIT License",
     *          url="https://opensource.org/licenses/MIT"
     *      )
     * )
     * 
     * @OAS\SecurityScheme(
     *      securityScheme="bearer_token",
     *      type="http",
     *      scheme="bearer"
     * )
     *
     */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
