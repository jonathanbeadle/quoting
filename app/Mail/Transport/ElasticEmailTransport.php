<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ElasticEmailTransport extends AbstractTransport
{
    protected $apiKey;
    protected $client;
    protected $maxRetries = 3;
    protected $retryDelay = 5;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => 'https://api.elasticemail.com/v2/',
            'timeout'  => 30,
        ]);
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $payload = $this->buildPayload($email);
        
        $attempt = 0;
        do {
            try {
                if ($attempt > 0) {
                    // Exponential backoff
                    $sleepSeconds = $this->retryDelay * pow(2, $attempt - 1);
                    sleep($sleepSeconds);
                }

                $response = $this->client->post('email/send', [
                    'form_params' => array_merge($payload, [
                        'apikey' => $this->apiKey
                    ])
                ]);
                
                $result = json_decode((string) $response->getBody(), true);
                
                if (isset($result['success']) && $result['success']) {
                    Log::info('Email sent successfully via Elastic Email', [
                        'message_id' => $result['data']['messageid'] ?? null,
                        'transaction_id' => $result['data']['transactionid'] ?? null
                    ]);
                    return;
                }
                
                throw new \Exception($result['error'] ?? 'Unknown error occurred');
                
            } catch (RequestException $e) {
                $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
                $errorBody = $e->getResponse() ? (string) $e->getResponse()->getBody() : '';
                
                if ($this->shouldRetry($statusCode, $errorBody) && $attempt < $this->maxRetries) {
                    Log::warning('Elastic Email temporary failure, will retry', [
                        'attempt' => $attempt + 1,
                        'status_code' => $statusCode,
                        'error' => $errorBody
                    ]);
                    $attempt++;
                    continue;
                }
                
                throw new \Exception('Failed to send email via Elastic Email: ' . $errorBody);
            }
        } while ($attempt < $this->maxRetries);
        
        throw new \Exception('Failed to send email after ' . $this->maxRetries . ' attempts');
    }

    protected function shouldRetry(int $statusCode, string $errorBody): bool
    {
        // Retry on rate limits and greylisting
        if ($statusCode === 429 || $statusCode === 451) {
            return true;
        }
        
        // Retry on specific error messages
        $retryPhrases = [
            'rate limited',
            'greylisted',
            'try again later',
            'temporary failure'
        ];
        
        foreach ($retryPhrases as $phrase) {
            if (stripos($errorBody, $phrase) !== false) {
                return true;
            }
        }
        
        return false;
    }

    protected function buildPayload(Email $email): array
    {
        $payload = [
            'to' => implode(',', array_map([$this, 'formatAddress'], $email->getTo())),
            'subject' => $email->getSubject(),
            'from' => $this->formatAddress($email->getFrom()[0]),
            'fromName' => $email->getFrom()[0]->getName(),
        ];

        // Add HTML body
        if ($email->getHtmlBody()) {
            $payload['bodyHtml'] = $email->getHtmlBody();
            $payload['bodyText'] = $email->getTextBody() ?: strip_tags($email->getHtmlBody());
        } elseif ($email->getTextBody()) {
            $payload['bodyText'] = $email->getTextBody();
        }
        
        // Add CC recipients if any
        if (!empty($email->getCc())) {
            $payload['cc'] = implode(',', array_map([$this, 'formatAddress'], $email->getCc()));
        }

        // Add custom headers
        $headers = [];
        foreach ($email->getHeaders()->all() as $header) {
            $headers[$header->getName()] = $header->getBodyAsString();
        }
        if (!empty($headers)) {
            $payload['headers'] = json_encode($headers);
        }
        
        return $payload;
    }

    protected function formatAddress(Address $address): string
    {
        return $address->getAddress();
    }

    public function __toString(): string
    {
        return 'elastic-email';
    }
}