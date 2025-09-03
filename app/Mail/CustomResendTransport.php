<?php

namespace App\Mail;

use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\MessageConverter;
use GuzzleHttp\Client;

class CustomResendTransport extends AbstractTransport
{
    protected $client;
    protected $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'verify' => false,
            'timeout' => 30,
        ]);
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $envelope = $message->getEnvelope();

        $response = $this->client->post('https://api.resend.com/emails', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'from' => $envelope->getSender()->toString(),
                'to' => array_map(fn($addr) => $addr->toString(), $envelope->getRecipients()),
                'subject' => $email->getSubject(),
                'html' => $email->getHtmlBody(),
                'text' => $email->getTextBody(),
            ]
        ]);
    }

    public function __toString(): string
    {
        return 'custom-resend';
    }
}