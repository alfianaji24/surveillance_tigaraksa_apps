<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $client;
    protected $apiKey;
    protected $model;
    protected $maxTokens;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-1.5-flash');
        $this->maxTokens = config('services.gemini.max_tokens', 1000);
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';
    }

    /**
     * Generate text completion
     */
    public function generateText(string $prompt, array $options = [])
    {
        try {
            $url = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";
            
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? 0.7,
                    'maxOutputTokens' => $options['max_tokens'] ?? $this->maxTokens,
                    'topK' => 40,
                    'topP' => 0.95,
                ]
            ];

            $response = $this->client->post($url, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }

            throw new \Exception('Invalid response format from Gemini API');
        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            throw new \Exception('Failed to generate text: ' . $e->getMessage());
        }
    }

    /**
     * Generate code
     */
    public function generateCode(string $description, string $language = 'php')
    {
        $prompt = "Generate {$language} code for: {$description}. Provide only the code without explanation. Make sure the code is complete and functional.";
        
        return $this->generateText($prompt, [
            'temperature' => 0.3,
            'max_tokens' => 1500
        ]);
    }

    /**
     * Explain code
     */
    public function explainCode(string $code, string $language = 'php')
    {
        $prompt = "Explain this {$language} code in simple terms:\n\n{$code}\n\nProvide a clear explanation of what the code does, its purpose, and how it works.";
        
        return $this->generateText($prompt, [
            'temperature' => 0.5,
            'max_tokens' => 800
        ]);
    }

    /**
     * Generate blog post or article
     */
    public function generateBlogPost(string $topic, int $words = 500)
    {
        $prompt = "Write a comprehensive blog post about '{$topic}' in approximately {$words} words. Include an engaging title, proper structure with introduction, main body, and conclusion. Make it informative and interesting to read.";
        
        return $this->generateText($prompt, [
            'temperature' => 0.8,
            'max_tokens' => $words * 2
        ]);
    }

    /**
     * Translate text
     */
    public function translateText(string $text, string $fromLang, string $toLang)
    {
        $prompt = "Translate the following text from {$fromLang} to {$toLang}. Provide only the translation without any additional explanation:\n\n{$text}";
        
        return $this->generateText($prompt, [
            'temperature' => 0.3,
            'max_tokens' => 1000
        ]);
    }

    /**
     * Summarize text
     */
    public function summarizeText(string $text, int $maxLength = 200)
    {
        $prompt = "Summarize the following text in approximately {$maxLength} characters or less. Capture the main points and key information:\n\n{$text}";
        
        return $this->generateText($prompt, [
            'temperature' => 0.5,
            'max_tokens' => 300
        ]);
    }

    /**
     * Generate email
     */
    public function generateEmail(string $purpose, string $recipient, array $details = [])
    {
        $detailsText = !empty($details) ? "\nAdditional details to include: " . implode(', ', $details) : '';
        $prompt = "Write a professional email to {$recipient} about {$purpose}{$detailsText}. Include a clear subject line and proper email format with greeting, body, and closing.";
        
        return $this->generateText($prompt, [
            'temperature' => 0.7,
            'max_tokens' => 600
        ]);
    }

    /**
     * Generate social media post
     */
    public function generateSocialMediaPost(string $topic, string $platform = 'twitter')
    {
        $maxLength = $platform === 'twitter' ? 280 : 500;
        $prompt = "Write an engaging and professional {$platform} post about: {$topic}. Keep it under {$maxLength} characters. Make it suitable for the platform's audience and include relevant hashtags if appropriate.";
        
        return $this->generateText($prompt, [
            'temperature' => 0.8,
            'max_tokens' => 200
        ]);
    }

    /**
     * Chat with AI
     */
    public function chat(array $messages, array $options = [])
    {
        try {
            $url = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";
            
            // Convert messages to Gemini format
            $contents = [];
            foreach ($messages as $message) {
                $contents[] = [
                    'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [
                        ['text' => $message['content']]
                    ]
                ];
            }

            $payload = [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? 0.7,
                    'maxOutputTokens' => $options['max_tokens'] ?? $this->maxTokens,
                    'topK' => 40,
                    'topP' => 0.95,
                ]
            ];

            $response = $this->client->post($url, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }

            throw new \Exception('Invalid response format from Gemini API');
        } catch (\Exception $e) {
            Log::error('Gemini Chat Error: ' . $e->getMessage());
            throw new \Exception('Failed to get AI response: ' . $e->getMessage());
        }
    }

    /**
     * Check if service is available
     */
    public function isAvailable()
    {
        try {
            $url = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";
            
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Hello']
                        ]
                    ]
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 10
                ]
            ];

            $response = $this->client->post($url, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
}
