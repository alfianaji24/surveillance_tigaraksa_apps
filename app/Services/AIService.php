<?php

namespace App\Services;

use OpenAI;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $client;
    protected $model;
    protected $maxTokens;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openai.api_key'));
        $this->model = config('services.openai.model', 'gpt-3.5-turbo');
        $this->maxTokens = config('services.openai.max_tokens', 1000);
    }

    /**
     * Generate text completion
     */
    public function generateText(string $prompt, array $options = [])
    {
        try {
            $response = $this->client->chat()->create([
                'model' => $options['model'] ?? $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => $options['max_tokens'] ?? $this->maxTokens,
                'temperature' => $options['temperature'] ?? 0.7,
            ]);

            return $response->choices[0]->message->content;
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            throw new \Exception('Failed to generate text: ' . $e->getMessage());
        }
    }

    /**
     * Generate code
     */
    public function generateCode(string $description, string $language = 'php')
    {
        $prompt = "Generate {$language} code for: {$description}. Provide only the code without explanation.";
        
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
        $prompt = "Explain this {$language} code in simple terms:\n\n{$code}";
        
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
        $prompt = "Write a blog post about '{$topic}' in approximately {$words} words. Include an engaging title and proper structure.";
        
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
        $prompt = "Translate the following text from {$fromLang} to {$toLang}:\n\n{$text}";
        
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
        $prompt = "Summarize the following text in {$maxLength} characters or less:\n\n{$text}";
        
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
        $detailsText = !empty($details) ? "\nDetails: " . implode(', ', $details) : '';
        $prompt = "Write a professional email to {$recipient} about {$purpose}{$detailsText}. Include subject line.";
        
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
        $prompt = "Write a engaging {$platform} post about: {$topic}. Keep it under {$maxLength} characters.";
        
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
            $response = $this->client->chat()->create([
                'model' => $options['model'] ?? $this->model,
                'messages' => $messages,
                'max_tokens' => $options['max_tokens'] ?? $this->maxTokens,
                'temperature' => $options['temperature'] ?? 0.7,
            ]);

            return $response->choices[0]->message->content;
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            throw new \Exception('Failed to get AI response: ' . $e->getMessage());
        }
    }
}
