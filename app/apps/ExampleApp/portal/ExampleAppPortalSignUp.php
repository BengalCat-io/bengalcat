<?php

namespace Bc\App\Apps\ExampleApp\Portal;

use Bc\App\Core\Util;
use Bc\App\Apps\ExampleApp\ExampleAppUtil;

class ExampleAppPortalSignUp extends ExampleAppPortal {

    protected function init()
    {
        parent::init();
        
        // Need to process the signup form if it's filled out.
        $this->data->uri = $this->uri;
        $this->data->failMessage = '';
        
        // signup data was submitted, validate it.
        if (!$this->bc->isEmptyQueryRequest('signup_form_submitted')) {
            $required = ['name_first', 'name_last', 'email'];
            $notNull = ['name_first', 'name_last', 'email'];
            $valid = (object) $this->validateForm($required, $notNull, [], $form = 'Feedback');
            
            if (!$valid) {
                Util::redirect($this->uri . '?error=' . $valid['error']);
            }

            if ($valid) {
                $this->doValidationAndRedirect();
            }
        
        }
        
        // Unset Password
        $this->data->formData = $this->formData;
        
        $this->render(
            $this->getThemePart('/src/main/signup.php'),
            $this->data,
            [
                '[:nav]'  => $this->nav->getNav(true, $this->uri, ['/signup/']),
                '[:body]' => $this->body,
                '[:signup form]' => $this->getThemePartContents(
                        '/src/tokenHTML/forms/signup.php', $this->data),
                '[:contact form]' => $this->getThemePartContents(
                        '/src/tokenHTML/forms/contact.php', $this->data)
            ]
        );
    }
    
    protected function doValidationAndRedirect()
    {
        // Make api create the user.
        $this->createUser();
        
        
        Util::redirectShortPath('/?success=' . 'Check your email for verification link!');
    }
    
    protected function createUser()
    {
        $apiRequestUri = Util::getBasePath() . '/api/os/create/account/';
        
        $postBody = json_encode([
            'data' => [
                'name_first' => $this->formData->name_first,
                'name_last'  => $this->formData->name_last,
                'email'      => $this->formData->email,
                'phone'      => $this->formData->phone,
            ]
        ]);
        
        try {
            $response = Util::makeCurlCall($apiRequestUri, $postBody, true, false, [
                'Authorization' => $this->settings->apiToken
            ]);
            $data = json_decode($response);
        } catch (Exception $ex) {
            $this->triggerError(500, 'Could not connect to API to create a user.');
        }

        return $data;
    }
}

