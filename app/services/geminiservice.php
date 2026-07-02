<?php

require "vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

$key = $_ENV['GEMINI_KEY'];
$model = 'gemini-3.5-flash';
$gemini = new Gemini($key, $model);

class Gemini
{
    private Client $http;
    private string $apiKey;
    private string $model;

    public function __construct($apiKey, $model)
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
        $this->http = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/interactions',
            'timeout' => 30
        ]);
    }

    public function ask(array $respostas)
    {
        $promptTexto = $this->montarPrompt($respostas);
        var_dump($promptTexto); // Debug: Output the prompt text
        exit;

        try {
            $response = $this->http->post('', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $this->apiKey,
                ],
                'json' => [
                    'model' => $this->model,
                    'system_instruction' => "Você é um escritor de cartas emocionais e personalizadas. Crie uma carta única baseada nas perguntas e respostas fornecidas. A carta deve ser envolvente, emocional e transmitir sentimentos profundos. Evite clichês e seja criativo. Use uma linguagem que toque o coração do leitor.",
                    'input' => $promptTexto,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $this->extractText($data);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $status = $e->getResponse()->getStatusCode();
                $body   = $e->getResponse()->getBody()->getContents();

                if ($status === 429) {
                    throw new \RuntimeException("Limite de requisições excedido: {$body}");
                }
                throw new \RuntimeException("Erro da API ({$status}): {$body}");
            }
            throw new \RuntimeException("Erro de conexão: " . $e->getMessage());
        }
    }

    private function montarPrompt(array $respostas): string
    {
        $linhas = [];
        foreach ($respostas as $item) {
            $linhas[] = "{$item['question']}\nR: {$item['answer']}";
        }
        return implode("\n\n", $linhas);
    }

    public function extractText(array $data)
    {
        foreach ($data['steps'] ?? [] as $step) {
            if ($step['type'] === 'model_output') {
                foreach ($step['content'] ?? [] as $part) {
                    if ($part['type'] === 'text') {
                        return $part['text'];
                    }
                }
            }
        }
        return '';
    }
}

$respostas = [
    ['question' => 'Qual é a ocasião especial para a carta?', 'answer' => 'Dia dos pais'],
    ['question' => 'Qual é a relação entre o remetente e o destinatário?', 'answer' => 'Filho para pai'],
    ['question' => 'Qual o nome do seu pai?', 'answer' => 'Ciclaninho Soares'],
    ['question' => 'Qual é a mensagem principal que você deseja transmitir na carta?', 'answer' => 'Expressar gratidão e amor pelo meu pai, destacando momentos especiais que compartilhamos e como ele me inspirou ao longo da vida.'],
    ['question' => 'Há alguma história ou memória específica que você gostaria de incluir na carta?', 'answer' => 'Sim, gostaria de mencionar a vez em que meu pai me ensinou a andar de bicicleta e como ele sempre esteve lá para me apoiar em todos os momentos.'],
    ['question' => 'Qual é o tom desejado para a carta?', 'answer' => 'Emocional e inspirador'],
];

echo '<pre>';
echo print_r($gemini->ask($respostas));
echo '</pre>';