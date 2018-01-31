<?php

namespace Bc\App\Services\SendGridService;

/*
 * This is a really super lightweight version with limited options.
 * Use the provided PHP library from SendGrid for more robust solutions.
 *
 */

class SendGridServiceMail {

    protected $url = 'https://api.sendgrid.com/v3/mail/send';
    protected $mail;
    protected $personalization;

    public function __construct()
    {
        $this->mail = (object) [];
        $this->personalization = (object) [];
    }

    protected function setObjectProp($prop, $value, $object = 'mail')
    {
        $this->{$object}->{$prop} = $value;
    }

    protected function addToObjectProp($prop, $add, $object = 'mail')
    {
        if (!isset($this->{$object}->{$prop})) {
            $this->setObjectProp($prop, [], $object);
        }

        $this->{$object}->{$prop}[] = $add;
    }

    public function addPersonalization($email, $name = '', $type = 'to')
    {
        $add = [
            'email' => $email
        ];

        if (!in_array($type, ['to' , 'bcc', 'cc'])) {
            throw new \Exception('Available personalizations types are: to, bcc, cc.', 500);
        }

        if (!empty($name)) {
            $add['name'] = $name;
        }

        $this->addToObjectProp($type, (object) $add, 'personalization');
        $this->setObjectProp('personalizations', [$this->personalization]);
    }

    public function addSubject($subject)
    {
        $this->setObjectProp('subject', $subject, 'personalization');
        $this->setObjectProp('personalizations', [$this->personalization]);
    }

    public function addFrom($email, $name = '')
    {
        $this->setObjectProp('from', (object) [
            'email' => $email,
            'name' => $name
        ]);
    }

    public function addReplyTo($email, $name = '')
    {
        $this->setObjectProp('reply_to', (object) [
            'email' => $email,
            'name' => $name
        ]);
    }

    public function addContent($content, $type = 'text/html')
    {
        $this->addToObjectProp('content', (object) [
            'type' => $type,
            'value' => $content
        ]);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getMail()
    {
        return $this->mail;
    }
}

