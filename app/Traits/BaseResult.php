<?php

namespace App\Traits;

use App\Enum\ResponseCodeConst;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use SplFileInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Trait BaseResult
 *
 * @package Modules\Base\Http\Traits
 */
trait BaseResult
{
    /**
     * Ok resource
     * (Responses.Base.Ok)
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    protected function okResult(mixed $data = [], string $message = ''): JsonResponse
    {
        return $this->jsonResult([
            'code' => ResponseCodeConst::CODE_OK,
            'data' => $data,
            'message' => $message ?: __('base::messages.success'),
        ], 200);
    }

    /**
     * BadRequest resource
     * (Responses.Base.BadRequest)
     *
     * @param string $code
     * @param string $message
     * @param string $type
     * @return JsonResponse
     */
    protected function badRequestResult(string $code = '', string $message = '', string $type = ''): JsonResponse
    {
        return $this->jsonResult([
            'code' => $code ?: ResponseCodeConst::CODE_BAD_REQUEST,
            'message' => $message ?: __('base::messages.error.bad_request'),
            'type' => $type ?: 'BadRequestException',
        ], 400);
    }

    /**
     * Unauthorized resource
     * (Responses.Base.Unauthorized)
     *
     * @param string $code
     * @param string $message
     * @param string $type
     * @return JsonResponse
     */
    protected function unauthorizedResult(string $code = '', string $message = '', string $type = ''): JsonResponse
    {
        return $this->jsonResult([
            'code' => $code ?: ResponseCodeConst::CODE_UNAUTHORIZED,
            'message' => $message ?: __('base::messages.error.unauthorized'),
            'type' => $type ?: 'AuthorizationException',
        ], 401);
    }

    /**
     * Forbidden resource
     * (Responses.Base.Forbidden)
     *
     * @param string $code
     * @param string $message
     * @param string $type
     * @return JsonResponse
     */
    protected function forbiddenResult(string $code = '', string $message = '', string $type = ''): JsonResponse
    {
        return $this->jsonResult([
            'code' => $code ?: ResponseCodeConst::CODE_FORBIDDEN,
            'message' => $message ?: __('base::messages.error.forbidden'),
            'type' => $type ?: 'ForbiddenException',
        ], 403);
    }

    /**
     * NotFound resource
     * (Responses.Base.NotFound)
     *
     * @param string $code
     * @param string $message
     * @param string $type
     * @param Exception|null $e
     * @return JsonResponse
     */
    protected function notFoundResult(
        string $code = '',
        string $message = '',
        string $type = '',
        Exception $e = null
    ): JsonResponse {
        $message = $message ?: ($e ? $e->getMessage() : __('base::messages.error.not_found'));
        return $this->jsonResult([
            'code' => $code ?: ResponseCodeConst::CODE_NOT_FOUND,
            'message' => $message,
            'type' => $this->genErrorType($e, ($type ?: 'NotFoundException')),
            'errors' => $this->genErrorsFormat($e),
        ], 404);
    }

    /**
     * Method Not Allowed resource
     * (Responses.Base.MethodNotAllowed)
     *
     * @param Exception|null $e
     * @param string $message
     * @return JsonResponse
     */
    protected function methodNotAllowedResult(Exception $e = null, string $message = ''): JsonResponse
    {
        $message = $message ?: __('base::messages.error.method_not_allowed');
        return $this->jsonResult([
            'code' => ResponseCodeConst::CODE_METHOD_NOT_ALLOWED,
            'message' => $message,
            'type' => $this->genErrorType($e, 'MethodNotAllowedHttpException'),
            'errors' => $this->genErrorsFormat($e),
        ], 405);
    }

    /**
     * UnprocessableEntity resource
     * (Responses.Base.UnprocessableEntity)
     *
     * @param string $code
     * @param string $message
     * @param string $type
     * @param array $errors
     * @return JsonResponse
     */
    protected function unprocessableEntityResult(
        string $code = '',
        string $message = '',
        string $type = '',
        mixed $errors = []
    ): JsonResponse {
        return $this->jsonResult([
            'code' => $code ?: ResponseCodeConst::CODE_UNPROCESSABLE_ENTITY,
            'message' => $message ?: __('base::messages.error.unprocessable_entity'),
            'type' => $type ?: 'UnprocessableEntityException',
            'errors' => $errors,
        ], 422);
    }

    /**
     * Internal Server Error resource
     * (Responses.Base.Error)
     *
     * @param Exception|null $e
     * @param string $message
     * @return JsonResponse
     */
    protected function errorResult(Exception $e = null, string $message = ''): JsonResponse
    {
        $message = $message ?: __('base::messages.error.internal_server_error');
        return $this->jsonResult([
            'code' => ResponseCodeConst::CODE_INTERNAL_SERVER_ERROR,
            'message' => $message,
            'type' => $this->genErrorType($e, 'ErrorException'),
            'errors' => $this->genErrorsFormat($e),
        ], 500);
    }

    /**
     * Gen Errors format for Response
     *
     * @param Exception|null $e
     * @return object|null
     */
    private function genErrorsFormat(Exception $e = null): ?object
    {
        return $e ? (object)[
            'class' => $e::class,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'msg' => $e->getMessage(),
        ] : null;
    }

    /**
     * Gen Error type for Response
     *
     * @param Exception|null $e
     * @param string $default
     * @return string
     */
    private function genErrorType(Exception $e = null, string $default = 'Exception'): string
    {
        $type = $e ? $e::class : $default;
        $types = explode('\\', $type);
        return count($types) ? $types[(count($types) - 1)] : $type;
    }

    /**
     *  response result format json
     *
     * @param mixed $data
     * @param int $httpStatus
     * @return JsonResponse
     */
    protected function jsonResult(mixed $data, int $httpStatus = 200): JsonResponse
    {
        return response()->json($data, $httpStatus);
    }

    /**
     * Response result format html
     *
     * @param array|string $view
     * @param mixed $data
     * @param int $httpStatus
     * @return Response
     */
    protected function htmlResult(array|string $view, mixed $data, int $httpStatus = 200): Response
    {
        return response()->view($view, $data, $httpStatus);
    }

    /**
     *  response result file
     *
     * @param SplFileInfo|string $file
     * @param array $headers
     * @return BinaryFileResponse
     */
    protected function fileResult(SplFileInfo|string $file, array $headers): BinaryFileResponse
    {
        return response()->file($file, $headers);
    }

    /**
     *  response result download
     *
     * @param SplFileInfo|string $file
     * @param string|null $name
     * @param array $headers
     * @return BinaryFileResponse
     */
    protected function downloadResult(
        SplFileInfo|string $file,
        string $name = null,
        array $headers = []
    ): BinaryFileResponse {
        return response()->download($file, $name, $headers);
    }

    /**
     * Return sample ok result
     *
     * @param mixed $data
     * @return JsonResponse
     */
    protected function sampleResult(mixed $data = []): JsonResponse
    {
        return $this->jsonResult($data, 200);
    }
}
