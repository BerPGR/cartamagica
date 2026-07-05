<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

$key = $_ENV['GEMINI_KEY'];
const MODEL = 'gemini-3.5-flash';
class GeminiChat
{
    private Client $http;
    private string $apiKey;

    private const SYSTEM_INSTRUCTION = <<<TEXT
Você é um entrevistador caloroso e curioso, conduzindo uma conversa para reunir informações
que serão usadas para escrever uma carta emocional e personalizada.

Regras:
- Faça UMA pergunta por vez, curta e natural, como numa conversa real.
- Baseie a próxima pergunta na resposta anterior, aprofundando ou explorando um novo ângulo relevante
  (ocasião, relação entre as pessoas, memórias específicas, sentimentos, tom desejado, etc).
- Não repita perguntas já respondidas.
- Não mencione que está seguindo um roteiro ou que vai escrever uma carta ainda.
- Responda APENAS com a pergunta, sem introduções tipo "Ótimo, agora me diga...".
TEXT;

    private const INSTRUCAO_FINAL = <<<TEXT
Você já tem informações suficientes. Agora escreva a carta final, com base em toda a conversa
até aqui. A carta deve ser envolvente, emocional, evitar clichês e transmitir sentimentos
profundos. Responda APENAS com o texto da carta, sem comentários antes ou depois.
TEXT;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->http = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/interactions',
            'timeout' => 30
        ]);
    }

    public function primeiraPergunta(): array
    {
        return $this->enviar('Vamos começar. Faça a primeira pergunta.', null, self::SYSTEM_INSTRUCTION);
    }

    public function proximaPergunta(string $resposta, string $previousId): array
    {
        return $this->enviar($resposta, $previousId, self::SYSTEM_INSTRUCTION);
    }

    public function gerarCartaFinal(string $ultimaResposta, string $previousId): array
    {
        $mensagem = $ultimaResposta . "\n\n" . self::INSTRUCAO_FINAL;
        return $this->enviar($mensagem, $previousId, self::SYSTEM_INSTRUCTION);
    }

    public function enviar(string $mensagem, ?string $previousId, string $systemInstructions)
    {
        $body = [
            'model' => MODEL,
            'system_instruction' => $systemInstructions,
            'input' => $mensagem,
        ];

        if ($previousId) {
            $body['previous_interaction_id'] = $previousId;
        }

        try {
            $response = $this->http->post('', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $this->apiKey
                ],
                'json' => $body
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return [
                'text' => $this->extractData($data),
                'interaction_id' => $data['id'] ?? null
            ];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $status = $e->getResponse()->getStatusCode();
                $body   = $e->getResponse()->getBody()->getContents();
                throw new \RuntimeException("Erro da API ({$status}): {$body}");
            }
            throw new \RuntimeException("Erro de conexão: " . $e->getMessage());
        }
    }

    public function extractData(array $data)
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
