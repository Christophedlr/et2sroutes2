<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel;


class Mailer
{
    /**
     * @var \Swift_SmtpTransport
     */
    private $transport;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(string $host, string $username, string $password, int $port = 25, string $encryption = null)
    {
        $this->transport = new \Swift_SmtpTransport($host, $port, $encryption);
        $this->transport
            ->setUsername($username)
            ->setPassword($password);

        $this->mailer = new \Swift_Mailer($this->transport);
    }

    /**
     * Create a new message
     *
     * @param string $subject
     * @param string $body
     * @param string $contentType
     * @param string $charset
     * @return \Swift_Message
     */
    public function newMessage(string $subject, string $body, string $contentType = 'text/plain', $charset = 'utf-8')
    {
        return new \Swift_Message($subject, $body, $contentType, $charset);
    }

    /**
     * Send a message
     *
     * @param \Swift_Message $message
     * @return int
     */
    public function send(\Swift_Message $message)
    {
        return $this->mailer->send($message);
    }
}
