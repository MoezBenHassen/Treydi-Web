<?php
declare(strict_types=1);
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GptClient
{
    public function __construct(
        private  HttpClientInterface $client,
        private ParameterBagInterface $parameterBag,
    )
    {
    }
    public function generate(string $prompt): string
    {
        $chatGPTUrl = $this->parameterBag->get('chat_gpt_api_url');
        $chatGPTApiKey = $this->parameterBag->get('chat_gpt_api_key');
        $response = $this->client->request(
           Request::METHOD_POST,
          $chatGPTUrl,
          [
              'headers' => [
                  'Authorization' => 'Bearer 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',
              ],
              'json' => [
                  'prompt' => $prompt,
                  "tempreture" => 0.5,
                  'model' =>'text-davinci-003',
              ],
          ]
        );
       $responseData = $response->toArray();
       dump($responseData);
        return 'error';
    }
}