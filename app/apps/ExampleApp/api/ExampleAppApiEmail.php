<?php

namespace Bc\App\Apps\ExampleApp\Api;

use Bc\App\Core\Util;
use Exception;

abstract class ExampleAppApiEmail extends ExampleAppApi {
    
    const EMAIL_TEMPLATE_DIR = APP_DIR . 'apps/ExampleApp/api/send/templates/';
    
    protected function buildEmail()
    {
        /** @note If you don't like this message + data combo,
         *  just override it in email method before sending email **/
        $this->message  = Util::getTemplateContents(
            $this->bc, self::EMAIL_TEMPLATE_DIR . $this->data->type . '.php', $this->data
        );
        
        $this->mail->addFrom(
            $this->settings->exampleAppEmail, 
            $this->settings->exampleAppEmailName,
            'from'
        );
        $this->mail->addPersonalization(
            $this->data->email,
            $this->data->name,
            'to'
        );
        
        if (!empty($this->data->cc)) {
            foreach ($this->data->cc as $cc) {
                $this->mail->addPersonalization($cc, '', 'cc');
            }
        }
        
        if (!empty($this->data->bcc)) {
            foreach ($this->data->bcc as $bcc) {
                $this->mail->addPersonalization($bcc, '', 'bcc');
            }
        }
    }
    
    protected function sendEmail()
    {
        try {
            $response = $this->sendGrid->sendRequest($this->mail->getUrl(), $this->mail->getMail(), 'POST');

            if ($response != 202) {
                throw new Exception('SendGrid Rejected Email: ' . json_encode($response), 500);
            }
        } catch (Exception $e) {
            $errorMessage = 'ERROR Send Email: ' . $this->data->type . ' - ';
            $errorData = [
                'code'         => $e->getCode(),
                'message'      => $e->getMessage(),
            ];
            
            error_log($errorMessage . json_encode($errorData));
            $this->triggerError(500, $errorMessage, $errorData);
        }
        
        return (object) [
            'success' => true,
            'type' => $this->data->type
        ];;
    }
    
    protected function sendPasswordReset()
    {
        $valid = $this->validateService->validateData(
            $this->data->data,
            'SendEmail',
            [
                'resetLink',
                'resetDisplay'
            ]
        );
        
        if (!$valid->success) {
            $this->triggerError(400, $valid);
        }
        
        $this->buildEmail();
        $this->mail->addContent($this->message);
        $this->mail->addSubject("Password Reset | RB Image Tracker");
        
        return $this->sendEmail();
    }
    
    protected function sendVerifyEmail()
    {
        $valid = $this->validateService->validateData(
            $this->data->data,
            'SendEmail',
            [
                'verifyLink',
                'verifyDisplay'
            ]
        );
        
        if (!$valid->success) {
            $this->triggerError(400, $valid);
        }
        
        $this->buildEmail();
        $this->mail->addContent($this->message);
        $this->mail->addSubject("Verify Email | RB Image Tracker - Sign Up Success!");
        
        return $this->sendEmail();
    }
    
}