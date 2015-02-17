<?php
namespace Model;

use Respect\Validation\Validator as v;

class ValidationService
{
    /**
     * @var array
     */
    private $errors = array();

    /**
     * Validate the input data.
     *
     * @param array $inputData
     */
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

    /**
     * Check is there any validation errors.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return (count($this->errors) > 0);
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
