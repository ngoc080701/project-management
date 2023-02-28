<?php

namespace App\Enum;

class ResponseCodeConst
{
    // For HTTP

    // Success
    public const CODE_OK                    = 'OK';
    // A bad request, please try again
    public const CODE_BAD_REQUEST           = 'BAD_REQUEST';
    // An exception, failed authorization attempt
    public const CODE_UNAUTHORIZED          = 'UNAUTHORIZED';
    // An exception, token has expired
    public const CODE_TOKEN_EXPIRED         = 'TOKEN_EXPIRED';
    // An exception, the Entity cannot handle
    public const CODE_UNPROCESSABLE_ENTITY  = 'UNPROCESSABLE_ENTITY';
    // You don't have permission to access
    public const CODE_FORBIDDEN             = 'FORBIDDEN';
    // The Resource you are looking for is not available
    public const CODE_NOT_FOUND             = 'NOT_FOUND';
    // An exception, Please contact technical department
    public const CODE_INTERNAL_SERVER_ERROR = 'INTERNAL_SERVER_ERROR';
    // An exception, The request method is not supported by the system
    public const CODE_METHOD_NOT_ALLOWED    = 'METHOD_NOT_ALLOWED';
    // An exception, the method {ClassName->MethodName} not found
    public const CODE_METHOD_NOT_FOUND      = 'METHOD_NOT_FOUND';
    // An exception, the model {ModelName} not found
    public const CODE_MODEL_NOT_FOUND       = 'MODEL_NOT_FOUND';
    // An exception, failed validation attempt
    public const CODE_FAILED_VALIDATION     = 'FAILED_VALIDATION';
    // An exception, failed validation attempt
    public const CODE_INVALID_SIGNATURE = 'INVALID_SIGNATURE';
    // An exception, failed validation attempt
    public const CODE_EMAIL_NOT_VERIFIED = 'EMAIL_NOT_VERIFIED';
    // :email exists
    public const CODE_EMAIL_EXIST = 'FAILED_VALIDATION_HAS_EMAIL_EXIST';


    // For Data

    // :data does not exist or has been deleted
    public const CODE_DATA_NOT_FOUND = 'DATA_NOT_FOUND';
    // :data invalid
    public const CODE_DATA_INVALID   = 'DATA_INVALID';
    // :data exists
    public const CODE_DATA_EXIST     = 'DATA_EXIST';
    // :data duplicate
    public const CODE_DATA_DUPLICATE = 'DATA_DUPLICATE';
}
