<?php
namespace Base
{
    class request
    {}

    class responce
    {
            public $status;
    }
    
    const OK = 200;
    const BadRequest = 400;
    const Forbidden = 403;
    const NotFound = 404;
    const Conflict = 409;
    const InternalError = 500;
}
?>
