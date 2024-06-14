<?php
namespace App\Http\Traits;

trait ResponseTrait
{
    public $status = true;
    public $message = "";
    public $errors = [];
    public $data = [];
    public $code = 200;

    public function returnResponse()
    {
        if (!$this->status) {
            return response()->json(["status" => $this->status, "message" => $this->message, "status_code" => $this->code], $this->code);
            return response()->json(["status" => $this->status, "message" => $this->message, "data" => $this->data, "status_code" => $this->code], $this->code);
        }
    }
}