<?php

namespace Bc\App\Apps\ExampleApp\Portal;

use Bc\App\Core\Util;
use Bc\App\Services\SendGridService\SendGridService;
use Bc\App\Services\SendGridService\SendGridServiceMail;
use Exception;

class ExampleAppPortalContact extends ExampleAppPortal {

    protected $redirectUrlBase;

    protected function init()
    {
        if (!empty($this->bc->getQueryRequests())) {
            $this->redirectUrlBase = '/contact/#contact-admin';

            if (
                $this->bc->issetQueryParam('submituri') &&
                $this->bc->getQueryParam('submituri') == '/signup/'
            ) {
                $this->routeData->isGated = false; // not gated if using the signup page to contact admin.
                $this->redirectUrlBase = '/signup/#contact-admin';
            }

            parent::init();
            $this->submitContactForm();
        }

        parent::init();

        $this->render(
            $this->getThemePart('/src/main/contact.php'),
            $this->routeData,
            [
                '[:body]' => $this->body,
                '[:form]' => $this->getThemePartContents(
                        '/src/tokenHTML/forms/contact.php', $this->data)
            ]
        );
    }

    protected function submitContactForm()
    {
        // validate form data
        $required = ['email', 'message', 'is_match_feedback'];
        $notNull = ['email', 'message', 'is_match_feedback'];
        $boolStrings = ['is_match_feedback'];
        $valid = (object) $this->validateForm($required, $notNull, $boolStrings, $form = 'Feedback');

        if (!$valid) {
            Util::redirect($this->redirectUrlBase . '?error=Form did not send, check fields.');
        }

        if (empty($this->currentUser)) {
            $this->currentUser = Util::objectifyAssocArray([
                'data' => [
                    'user_id' => 'n\a',
                    'name' => $this->formData->name,
                    'email' => $this->formData->email
                ]
            ], true);
        }

        $details = [
            'User ID' => $this->currentUser->data->user_id,
        ];

        $this->data->currentUser = $this->currentUser->data;
        $details['User Email'] = $this->data->currentUser->email;


        $this->data->details = $details;
        $this->data->siteUrl = Util::getBasePath();
        $this->data->siteName = 'image-tracker.recoverybrands.com';
        $this->data->message = $this->formData->message;

        $this->sendContactEmail();
    }

    protected function sendContactEmail()
    {
        $message = $this->getThemePartContents('/src/templates/emails/contact.php', $this->data);

        $sendGrid = new SendGridService($this->settings->sendGridApiKey);

        try {
            $mail = new SendGridServiceMail();
            $mail->addContent($message);
            $mail->addPersonalization($this->settings->feedbackEmail, $this->settings->feedbackEmailName, 'to');
            $mail->addFrom($this->settings->adminEmail, $this->settings->feedbackEmailName);
            $mail->addReplyTo(
                    $this->data->currentUser->email,
                    $this->data->currentUser->name
            );
            $mail->addSubject('Contact Form / Bug Report Submitted');

            $response = $sendGrid->sendRequest($mail->getUrl(), $mail->getMail(), 'POST');

            if ($response != 202) {
                throw new Exception('SendGrid Rejected Email: ' . json_encode($response), 500);
            }
        } catch (Exception $e) {
            error_log('sendFeedbackEmail - ' . json_encode([
                'code'         => $e->getCode(),
                'message'      => $e->getMessage(),
            ]));
            Util::redirect(
                $this->redirectUrlBase . '?error=Email service down for Image Tracker Contact Form'
            );
        }

        Util::redirect(
            $this->redirectUrlBase . '?success=Feedback successfully sent!'
        );

        return $this;
    }
}

