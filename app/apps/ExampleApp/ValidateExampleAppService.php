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

namespace Bc\App\Apps\ExampleApp;

use Bc\App\Core\Services\ValidateService;
use Bc\App\Apps\ExampleApp\ExampleAppUtil;

class ValidateExampleAppService extends ValidateService {

    protected function validateAccountForm($requiredFields = [])
    {
        // Change Email
        if (!filter_var($this->formData->email, FILTER_VALIDATE_EMAIL)) {
            $this->fail("The email address provided does not seem to be a valid email address. Try again.");
        }

        // New Password
        $passwordFields = ['password_current', 'password_new', 'password_confirm'];
        $changePassword = ExampleAppUtil::atLeastOnePropSet($this->formData, $passwordFields, true);
        $completePassword = ExampleAppUtil::allPropsSet($this->formData, $passwordFields, true);
        $hashCurrentPassword = password_hash($this->formdata->password_current, PASSWORD_DEFAULT);

        if ($changePassword && !$completePassword) {
            return $this->fail("In order to change the password, you must fill out all password fields.");
        }

        if ($changePassword && $hashCurrentPassword != $this->route->getCurUserProp('password_hash')) {
            return $this->fail("Current Password does not match your actual current password.");
        }

        return $this->success();
    }

    protected function validateFeedbackForm($requiredFields = [])
    {
        // Email
        if (!filter_var($this->formData->email, FILTER_VALIDATE_EMAIL)) {
            $this->fail("The email address provided does not seem to be a valid email address. Try again.");
        }

        return $this->success();
    }

    protected function validateSignUpForm($requiredFields = [])
    {
        $requiredFields = ['name', 'email'];
        $complete = ExampleAppUtil::allPropsSet($this->formData, $requiredFields, true);

        if (!$complete) {
            return $this->fail("Please check that you've completed all fields.");
        }

        // Email
        if (!filter_var($this->formData->email, FILTER_VALIDATE_EMAIL)) {
            $this->fail("The email address provided does not seem to be a valid email address. Try again.");
        }

        return $this->success();
    }
    
    protected function validateSignUpForm($requiredFields = [])
    {
        $requiredFields = ['name', 'email'];
        $complete = ExampleAppUtil::allPropsSet($this->formData, $requiredFields, true);

        if (!$complete) {
            return $this->fail("Please check that you've completed all fields.");
        }

        // Email
        if (!filter_var($this->formData->email, FILTER_VALIDATE_EMAIL)) {
            $this->fail("The email address provided does not seem to be a valid email address. Try again.");
        }

        return $this->success();
    }

    protected function validateAddUserData($requiredFields = [])
    {
        $requiredFields = [
            'name',
            'email',
            'password_hash',
            'ttl',
            'token_exp',
            'password_reset',
            'is_deleted',
            'date_created',
            'date_deleted',
            'date_modified'
        ];

        $propsNotSet = [];
        $complete    = ExampleAppUtil::allPropsSet(
                        $this->data, $requiredFields, false, $propsNotSet);

        if (!$complete) {
            return $this->fail("Some fields were not set (or are empty): ", ['propsNotSet' => $propsNotSet]);
        }

        // Email
        if (!filter_var($this->data->email, FILTER_VALIDATE_EMAIL)) {
            $this->fail("The email address provided does not seem to be a valid email address. Try again.");
        }

        return $this->success();
    }
    
    protected function validateAddAccountData($requiredFields = [])
    {
        $requiredFields = [
            'name',
            'organization',
            'industry',
            'phone',
            'is_deleted',
            'date_created',
            'date_deleted',
            'date_modified'
        ];

        $propsNotSet = [];
        $complete    = ExampleAppUtil::allPropsSet(
                        $this->data, $requiredFields, false, $propsNotSet);

        if (!$complete) {
            return $this->fail("Some fields were not set (or are empty): ", ['propsNotSet' => $propsNotSet]);
        }

        return $this->success();
    }

    protected function validatePasswordResetDataData($requiredFields = [])
    {
        $requiredFields = [
            'siteUrl',
            'siteName',
            'name_first',
            'name_last',
            'name',
            'resetLink',
            'resetDisplay'
        ];

        $propsNotSet = [];
        $complete    = ExampleAppUtil::allPropsSet(
                        $this->data, $requiredFields, false, $propsNotSet);

        if (!$complete) {
            return $this->fail("Some fields were not set (or are empty): ", ['propsNotSet' => $propsNotSet]);
        }

        // Email
        if (!filter_var($this->data->email, FILTER_VALIDATE_EMAIL)) {
            $this->fail("The email address provided does not seem to be a valid email address. Try again.");
        }

        return $this->success();
    }

    protected function validateSendEmailData($requiredFields = [])
    {
        $propsNotSet = [];
        $complete    = ExampleAppUtil::allPropsSet(
                        $this->data, $requiredFields, false, $propsNotSet);

        if (!$complete) {
            return $this->fail("Some fields were not set (or are empty): ", ['propsNotSet' => $propsNotSet]);
        }

        // Email - To
        if (isset($this->data->email) && !filter_var($this->data->email, FILTER_VALIDATE_EMAIL)) {
            return $this->fail("The email address provided [{$this->data->email}] does not seem to be a valid email address. Try again.");
        }
        
        
        // Email - CC
        if (isset($this->data->cc) && is_array($this->data->cc)) {
            foreach ($this->data->cc as $ccEmail) {
                if (!filter_var($ccEmail, FILTER_VALIDATE_EMAIL)) {
                    return $this->fail("The cc email address provided [$ccEmail] does not seem to be a valid email address. Try again.");
                }
            }
        }
        
        // Email - BCC
        if (isset($this->data->bcc) && is_array($this->data->bcc)) {
            foreach ($this->data->bcc as $bccEmail) {
                if (!filter_var($bccEmail, FILTER_VALIDATE_EMAIL)) {
                    return $this->fail("The bcc email address provided [$bccEmail] does not seem to be a valid email address. Try again.");
                }
            }
        }

        return $this->success();
    }

}

