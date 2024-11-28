<?php

namespace App\Exceptions\Api;

use Exception;
use Illuminate\Support\MessageBag;

class ValidationException extends Exception
{
    protected $errors;

    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  \Exception|null  $previous
     * @return void
     */
    public function __construct(MessageBag $errors, $message = "Validation failed", $code = 422, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * Render the exception response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => $this->errors->toArray(),
        ], $this->getCode());
    }
}
