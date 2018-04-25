<?php

namespace Application\Util;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Trait ProblemResponseTrait
 *
 * @package Application\Util
 */
trait ProblemResponseTrait
{
    /**
     * @param int    $code
     * @param string $message
     * @param null   $type
     * @param null   $title
     * @param array  $additional
     *
     * @return ApiProblemResponse
     */
    private function problemResponse($code, $message, $type = null, $title = null, $additional = [])
    {
        return new ApiProblemResponse(new ApiProblem($code, $message, $type, $title, $additional));
    }

    /**
     * @param array  $validationMessages
     * @param string $message
     *
     * @return ApiProblemResponse
     */
    private function validationErrorResponse(array $validationMessages, $message = 'Failed Validation')
    {
        return $this->problemResponse(
            422,
            $message,
            null,
            null,
            ['validation_messages' => $validationMessages]
        );
    }
}
