<?php
declare(strict_types=1);
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Orhanerday\OpenAi\OpenAi;
class GptClient
{
    public function __construct(
        private  HttpClientInterface $client,
        private ParameterBagInterface $parameterBag,
    ){
    }
    public function generate(string $prompt): string {
        $open_ai_key = $this->parameterBag->get('OPENAI_API_KEY');
        $open_ai = new OpenAi($open_ai_key);
        $complete = $open_ai->completion([
            'model' => 'text-davinci-002',
            'prompt' => $prompt,
            'temperature' => 0.9,
            'max_tokens' => 150,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
        ]);
        $json = json_decode($complete,true);
        if (isset($json['choices'][0]['text'])) {
            return $json['choices'][0]['text'];
        }
        $json = 'une erreur est survenue';
        return $complete->choices[0]->text;
    }
}