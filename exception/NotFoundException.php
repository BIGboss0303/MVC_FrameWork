<?php
namespace App\Core\Exception;
class NotfoundException extends \Exception{
    protected $code=404;
    protected $message="Page not found";

}