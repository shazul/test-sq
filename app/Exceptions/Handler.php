<?php

namespace Pimeo\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Mail\Message;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * A list of the exception types that should not display a 500 error message.
     *
     * @var array
     */
    protected $sendBackExceptions = [
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        \Illuminate\Foundation\Validation\ValidationException::class,
        AuthorizationException::class,
        HttpResponseException::class,
    ];

    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new exception handler instance.
     *
     * @param  \Psr\Log\LoggerInterface $log
     * @param  Application $app
     */
    public function __construct(LoggerInterface $log, Application $app)
    {
        parent::__construct($log);

        $this->app = $app;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldntReport($e) || $this->app->environment('local', 'test')) {
            return;
        }

        parent::report($e);

        $this->sendNotification($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (!config('app.debug') && !$this->shouldSendBack($e)) {
            return response()->view('errors.503', [], 500);
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Get the exception data as array.
     *
     * @param  Exception $exception
     *
     * @return array
     */
    protected function getExceptionData(Exception $exception)
    {
        $data = [
            'code'   => $exception->getCode(),
            'error'  => $exception->getMessage(),
            'file'   => $exception->getFile(),
            'line'   => $exception->getLine(),
            'traces' => $exception->getTraceAsString(),
        ];

        if (!is_null(auth()->user())) {
            $data['user_id'] = auth()->user()->id;
        }

        return $data;
    }

    /**
     * Get the mailer instance.
     *
     * @return Mailer|bool
     */
    protected function getMailer()
    {
        try {
            $mailer = app(Mailer::class);
        } catch (Exception $e) {
            $mailer = false;
        }

        return $mailer;
    }

    /**
     * Send a notification to the re
     *
     * @param  Exception $exception
     *
     * @return void
     */
    protected function sendNotification(Exception $exception)
    {
        if (!($mailer = $this->getMailer()) || !env('ERROR_REPORTING_EMAIL', false)) {
            return;
        }

        $data = $this->getExceptionData($exception);

        $mailer->send(
            'errors.email',
            $data,
            function (Message $message) {
                $message
                    ->subject('[Soprema] API/PIM error')
                    ->from('error-reporter@soprema.com', 'Error Reporter')
                    ->to(explode(',', env('ERROR_REPORTING_EMAIL')))
                    ->priority(1);
            }
        );
    }

    /**
     * Determine if the exception is in the "send back" list.
     *
     * @param  Exception $exception
     *
     * @return bool
     */
    protected function shouldSendBack(Exception $exception)
    {
        return in_array(get_class($exception), $this->sendBackExceptions);
    }
}
