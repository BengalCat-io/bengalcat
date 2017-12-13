<?php

/**
 * Edit your validate service however you like.
 *
 * Create a method "validateFormName",
 * which uses $form for FormName - passed to "validateForm"
 *
 * Write the validation in that method. Use $this->success(), or $this->fail()
 * in your method to return result of validation process.
 */

namespace Bc\App\Core\Services;

class ValidateService {

    protected $data;
    protected $form;
    protected $formData;
    protected $route;

    public function __construct($route)
    {
        $this->route = $route; // can only use public methods/props
        return $this;
    }

    public function validateData($data, $action, $requiredFields = [])
    {
        $this->data = $data;
        return $this->{'validate' . $action . 'Data'}($requiredFields);
    }

    public function validateForm($formData, $form, $requiredFields = [])
    {
        $this->formData = $formData;
        return $this->{'validate' . $form . 'Form'}($requiredFields);
    }

    protected function success()
    {
        return (object) ['success' => true];
    }

    protected function fail($message, $data = null)
    {
        $fail = (object) ['success' => false, 'error' => $message];

        if ($data !== null) {
            $fail->data = $data;
        }

        return $fail;
    }

}

