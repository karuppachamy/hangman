<?php
namespace Model;

use Respect\Validation\Validator as v;

class ValidationService
{
    private $errors = array();
    
    function __construct()
    {
       
    }
    
    public function validateInput($inputData)
    {
        if (!v::string()->notEmpty()->validate($inputData)) {
            $this->errors = 'Please give some inputs';
        }
        
        if (!v::json()->validate($inputData)) {
            $this->errors = 'Not a valid JSON';
        }

        if (v::string()->notEmpty()->validate($inputData) && v::json()->validate($inputData)) {

            if (!v::key('char')->validate(json_decode($inputData, true))) {
                $this->errors[] = 'Required Key char is not present in the input';
            }
        }
    }
    
    public function hasErrors() {
        return (count($this->errors) > 0);
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}
