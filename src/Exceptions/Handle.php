<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use \Carbon\Carbon;

class Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render(Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => $this->messages(
                    $exception->getModel(),
                ),
                'timespan' => Carbon::now()->toDateTimeLocalString()
            ], 404);
        }
    }

    protected function messages($item)
    {
        $messages = [
            'App\Model\Country' => 'Country was not found or was deleted!',
            'App\Model\City' => 'City type was not found or was deleted!',
        ];


        return $messages[$item];
    }
}
