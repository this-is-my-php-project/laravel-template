<?php
namespace App\Exceptions;

class Code
{
    const SUCCESS = 200;
    const SUCCESSFUL = 207;
    const REDIRECT = 307;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const FAILED = 500;
    const LOGIN_ABNORMALLY = -405;
    const INVALID_REQUEST = -406;
    const INVALID_PASSWORD = -407;
    const INVALID_PIN = -408;
    const INSUFFICIENT_BALANCE = -409;
    const ACCOUNT_NOT_MATCH = -410;
    const INVALID_VERIFICATION_CODE = -413;
}