<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;
use App\Services\GeminiService;

class AIController extends Controller
{
    protected $aiService;
    protected $geminiService;

    public function __construct(AIService $aiService, GeminiService $geminiService)
    {
        $this->aiService = $aiService;
        $this->geminiService = $geminiService;
    }

    /**
     * Get AI service based on provider
     */
    private function getAIService($provider = 'openai')
    {
        return $provider === 'gemini' ? $this->geminiService : $this->aiService;
    }

    /**
     * Show AI dashboard
     */
    public function index()
    {
        return view('ai.dashboard');
    }

    /**
     * Generate text
     */
    public function generateText(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
            'temperature' => 'sometimes|numeric|between:0,2',
            'max_tokens' => 'sometimes|integer|min:1|max:4000',
            'provider' => 'sometimes|string|in:openai,gemini'
        ]);

        try {
            $provider = $request->provider ?? 'openai';
            $aiService = $this->getAIService($provider);
            
            $result = $aiService->generateText(
                $request->prompt,
                $request->only(['temperature', 'max_tokens'])
            );

            return response()->json([
                'success' => true,
                'result' => $result,
                'provider' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate code
     */
    public function generateCode(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:1000',
            'language' => 'sometimes|string|in:php,javascript,python,java,cpp,html,css',
            'provider' => 'sometimes|string|in:openai,gemini'
        ]);

        try {
            $provider = $request->provider ?? 'openai';
            $aiService = $this->getAIService($provider);
            
            $result = $aiService->generateCode(
                $request->description,
                $request->language ?? 'php'
            );

            return response()->json([
                'success' => true,
                'result' => $result,
                'provider' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Explain code
     */
    public function explainCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:2000',
            'language' => 'sometimes|string|in:php,javascript,python,java,cpp,html,css',
            'provider' => 'sometimes|string|in:openai,gemini'
        ]);

        try {
            $provider = $request->provider ?? 'openai';
            $aiService = $this->getAIService($provider);
            
            $result = $aiService->explainCode(
                $request->code,
                $request->language ?? 'php'
            );

            return response()->json([
                'success' => true,
                'result' => $result,
                'provider' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate blog post
     */
    public function generateBlogPost(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:200',
            'words' => 'sometimes|integer|min:100|max:2000',
            'provider' => 'sometimes|string|in:openai,gemini'
        ]);

        try {
            $provider = $request->provider ?? 'openai';
            $aiService = $this->getAIService($provider);
            
            $result = $aiService->generateBlogPost(
                $request->topic,
                $request->words ?? 500
            );

            return response()->json([
                'success' => true,
                'result' => $result,
                'provider' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Translate text
     */
    public function translateText(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
            'from_lang' => 'required|string|max:10',
            'to_lang' => 'required|string|max:10',
            'provider' => 'sometimes|string|in:openai,gemini'
        ]);

        try {
            $provider = $request->provider ?? 'openai';
            $aiService = $this->getAIService($provider);
            
            $result = $aiService->translateText(
                $request->text,
                $request->from_lang,
                $request->to_lang
            );

            return response()->json([
                'success' => true,
                'result' => $result,
                'provider' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Summarize text
     */
    public function summarizeText(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:3000',
            'max_length' => 'sometimes|integer|min:50|max:500',
            'provider' => 'sometimes|string|in:openai,gemini'
        ]);

        try {
            $provider = $request->provider ?? 'openai';
            $aiService = $this->getAIService($provider);
            
            $result = $aiService->summarizeText(
                $request->text,
                $request->max_length ?? 200
            );

            return response()->json([
                'success' => true,
                'result' => $result,
                'provider' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate email
     */
    public function generateEmail(Request $request)
    {
        $request->validate([
            'purpose' => 'required|string|max:200',
            'recipient' => 'required|string|max:100',
            'details' => 'sometimes|array|max:5',
            'provider' => 'sometimes|string|in:openai,gemini'
        ]);

        try {
            $provider = $request->provider ?? 'openai';
            $aiService = $this->getAIService($provider);
            
            $result = $aiService->generateEmail(
                $request->purpose,
                $request->recipient,
                $request->details ?? []
            );

            return response()->json([
                'success' => true,
                'result' => $result,
                'provider' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate social media post
     */
    public function generateSocialMediaPost(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:200',
            'platform' => 'sometimes|string|in:twitter,facebook,linkedin,instagram',
            'provider' => 'sometimes|string|in:openai,gemini'
        ]);

        try {
            $provider = $request->provider ?? 'openai';
            $aiService = $this->getAIService($provider);
            
            $result = $aiService->generateSocialMediaPost(
                $request->topic,
                $request->platform ?? 'twitter'
            );

            return response()->json([
                'success' => true,
                'result' => $result,
                'provider' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chat with AI
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'conversation' => 'sometimes|array|max:10',
            'provider' => 'sometimes|string|in:openai,gemini'
        ]);

        try {
            $provider = $request->provider ?? 'openai';
            $aiService = $this->getAIService($provider);
            
            $messages = $request->conversation ?? [];
            $messages[] = ['role' => 'user', 'content' => $request->message];

            $result = $aiService->chat($messages);

            return response()->json([
                'success' => true,
                'result' => $result,
                'provider' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
