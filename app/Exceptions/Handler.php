<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponses;

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected array $apiHandlers = [
        ValidationException::class => 'handleValidation',
        ModelNotFoundException::class => 'handleModelNotFound',
        AuthenticationException::class => 'handleAuthentication',
        NotFoundHttpException::class => 'handleNotFoundHttp',
    ];

    private function handleValidation(ValidationException $exception): array
    {
        $errors = [];

        foreach ($exception->errors() as $key => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'status'  => 422,
                    'message' => $message,
                    'source'  => $key,
                ];
            }
        }

        return $errors;
    }

    private function handleModelNotFound(ModelNotFoundException $exception): array
    {
        return [
            [
                'status'  => 404,
                'message' => 'The resource cannot be found.',
                'source'  => $exception->getModel(),
            ],
        ];
    }

    private function handleNotFoundHttp(NotFoundHttpException $exception): array
    {
        $source = 'Route';

        if ($exception->getPrevious() instanceof ModelNotFoundException) {
            $source = $exception->getPrevious()->getModel();
        }

        return [
            [
                'status'  => 404,
                'message' => 'The resource cannot be found.',
                'source'  => $source,
            ],
        ];
    }

    private function handleAuthentication(AuthenticationException $exception): array
    {
        return [
            [
                'status'  => 401,
                'message' => 'Unauthenticated',
                'source'  => '',
            ],
        ];
    }

    public function render($request, Throwable $exception)
    {
        $className = get_class($exception);

        if (array_key_exists($className, $this->apiHandlers)) {
            $method = $this->apiHandlers[$className];
            return $this->error($this->$method($exception));
        }

        $status = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : 500;

        $source = config('app.debug')
            ? 'Line ' . $exception->getLine() . ': ' . $exception->getFile()
            : '';

        return $this->error([
            [
                'type'    => class_basename($className),
                'status'  => $status,
                'message' => config('app.debug') ? $exception->getMessage() : 'Server error',
                'source'  => $source,
            ],
        ]);
    }
}
