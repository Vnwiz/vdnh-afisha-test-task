<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sentry DSN
    |--------------------------------------------------------------------------
    |
    | The DSN tells the SDK where to send the events to. If this value is not
    | provided, the SDK will try to read it from the SENTRY_LARAVEL_DSN
    | environment variable. If that variable also does not exist, the SDK
    | will not send any events.
    |
    */

    'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),

    /*
    |--------------------------------------------------------------------------
    | Sentry Environment
    |--------------------------------------------------------------------------
    |
    | The environment your application is running in. This value will be
    | attached to all events sent to Sentry.
    |
    */

    'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV', 'production')),

    /*
    |--------------------------------------------------------------------------
    | Sentry Release
    |--------------------------------------------------------------------------
    |
    | The release version of your application. This value will be attached to
    | all events sent to Sentry.
    |
    */

    'release' => env('SENTRY_RELEASE'),

    /*
    |--------------------------------------------------------------------------
    | Sentry Traces Sample Rate
    |--------------------------------------------------------------------------
    |
    | The sample rate for performance monitoring. This value should be between
    | 0 and 1. For example, 0.5 means 50% of transactions will be sent to
    | Sentry.
    |
    */

    'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE', 0.0),

    /*
    |--------------------------------------------------------------------------
    | Sentry Profiles Sample Rate
    |--------------------------------------------------------------------------
    |
    | The sample rate for profiling. This value should be between 0 and 1.
    | For example, 0.5 means 50% of transactions will be profiled.
    |
    */

    'profiles_sample_rate' => env('SENTRY_PROFILES_SAMPLE_RATE', 0.0),

    /*
    |--------------------------------------------------------------------------
    | Sentry Ignore Exceptions
    |--------------------------------------------------------------------------
    |
    | List of exception classes that should not be sent to Sentry.
    |
    */

    'ignore_exceptions' => [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Validation\ValidationException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sentry Send Default PII
    |--------------------------------------------------------------------------
    |
    | If set to true, the SDK will send personally identifiable information
    | (PII) like user data, IP addresses, etc. to Sentry. Set to false to
    | disable this behavior.
    |
    */

    'send_default_pii' => env('SENTRY_SEND_DEFAULT_PII', false),

    /*
    |--------------------------------------------------------------------------
    | Sentry Max Breadcrumbs
    |--------------------------------------------------------------------------
    |
    | The maximum number of breadcrumbs to keep in memory. When this limit
    | is reached, the oldest breadcrumbs will be removed.
    |
    */

    'max_breadcrumbs' => env('SENTRY_MAX_BREADCRUMBS', 50),

];
