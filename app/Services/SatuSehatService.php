<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SatuSehatService
{
    private string $baseUrl;
    private string $authUrl;
    private ?string $clientId;
    private ?string $clientSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.satu_sehat.base_url', 'https://api.satusehat.kemkes.go.id');
        $this->authUrl = config('services.satu_sehat.auth_url', 'https://api.satusehat.kemkes.go.id/oauth2/token');
        $this->clientId = config('services.satu_sehat.client_id', '');
        $this->clientSecret = config('services.satu_sehat.client_secret', '');
    }

    /**
     * Get OAuth token for Satu Sehat API
     */
    public function getAccessToken(): string
    {
        return Cache::remember('satu_sehat_token', 3500, function () {
            $response = Http::asForm()->post($this->authUrl, [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]);

            if (!$response->successful()) {
                Log::error('Satu Sehat authentication failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('Failed to authenticate with Satu Sehat API');
            }

            return $response->json()['access_token'];
        });
    }

    /**
     * Search ICD-10 codes
     */
    public function searchICD10(string $query, int $limit = 20): array
    {
        try {
            $token = $this->getAccessToken();
            
            // Try CodeSystem lookup first
            $response = Http::withToken($token)
                ->get($this->baseUrl . '/CodeSystem/$lookup', [
                    'code' => $query,
                    'system' => 'http://hl7.org/fhir/sid/icd-10',
                    'displayLanguage' => 'id'
                ]);

            if ($response->successful() && !empty($response->json())) {
                return [$response->json()];
            }

            // Fallback to search
            $searchResponse = Http::withToken($token)
                ->get($this->baseUrl . '/CodeSystem', [
                    'url' => 'http://hl7.org/fhir/sid/icd-10',
                    'content' => $query,
                    '_count' => $limit
                ]);

            if ($searchResponse->successful()) {
                $data = $searchResponse->json();
                return $data['entry'] ?? [];
            }

            return $this->getMockICD10Data($query, $limit);

        } catch (\Exception $e) {
            Log::error('Satu Sehat ICD-10 search failed', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            return $this->getMockICD10Data($query, $limit);
        }
    }

    /**
     * Get detailed ICD-10 code information
     */
    public function getICD10Detail(string $code): array
    {
        try {
            $token = $this->getAccessToken();
            
            $response = Http::withToken($token)
                ->get($this->baseUrl . '/CodeSystem/icd-10/' . $code);

            if ($response->successful()) {
                return $response->json();
            }

            return $this->getMockICD10Detail($code);

        } catch (\Exception $e) {
            Log::error('Satu Sehat ICD-10 detail failed', [
                'code' => $code,
                'error' => $e->getMessage()
            ]);
            return $this->getMockICD10Detail($code);
        }
    }

    /**
     * Create Encounter in Satu Sehat
     */
    public function createEncounter(array $encounterData): array
    {
        try {
            $token = $this->getAccessToken();
            
            $fhirEncounter = $this->buildFHIREncounter($encounterData);
            
            $response = Http::withToken($token)
                ->post($this->baseUrl . '/Encounter', $fhirEncounter);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to create encounter: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Satu Sehat encounter creation failed', [
                'data' => $encounterData,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Update Encounter in Satu Sehat
     */
    public function updateEncounter(string $encounterId, array $encounterData): array
    {
        try {
            $token = $this->getAccessToken();
            
            $fhirEncounter = $this->buildFHIREncounter($encounterData);
            $fhirEncounter['id'] = $encounterId;
            
            $response = Http::withToken($token)
                ->put($this->baseUrl . '/Encounter/' . $encounterId, $fhirEncounter);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to update encounter: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Satu Sehat encounter update failed', [
                'encounter_id' => $encounterId,
                'data' => $encounterData,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get Patient information from Satu Sehat
     */
    public function getPatient(string $patientId): array
    {
        try {
            $token = $this->getAccessToken();
            
            $response = Http::withToken($token)
                ->get($this->baseUrl . '/Patient/' . $patientId);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Patient not found: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Satu Sehat patient lookup failed', [
                'patient_id' => $patientId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Build FHIR Encounter resource
     */
    private function buildFHIREncounter(array $data): array
    {
        $encounter = [
            'resourceType' => 'Encounter',
            'status' => 'finished',
            'class' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'AMB',
                'display' => 'ambulatory'
            ],
            'subject' => [
                'reference' => 'Patient/' . $data['patient_id'],
                'display' => $data['patient_name'] ?? 'Unknown Patient'
            ],
            'period' => [
                'start' => $data['encounter_date'],
                'end' => $data['encounter_date']
            ],
            'participant' => [
                [
                    'individual' => [
                        'reference' => 'Practitioner/' . $data['documenter_id'],
                        'display' => $data['documenter_name'] ?? 'Unknown Practitioner'
                    ],
                    'type' => [
                        [
                            'coding' => [
                                [
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
                                    'code' => 'ATND',
                                    'display' => 'attender'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'reasonCode' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://hl7.org/fhir/sid/icd-10',
                            'code' => $data['primary_diagnosis']['code'],
                            'display' => $data['primary_diagnosis']['display']
                        ]
                    ]
                ]
            ]
        ];

        // Add secondary diagnoses if present
        if (!empty($data['secondary_diagnoses'])) {
            foreach ($data['secondary_diagnoses'] as $diagnosis) {
                $encounter['reasonCode'][] = [
                    'coding' => [
                        [
                            'system' => 'http://hl7.org/fhir/sid/icd-10',
                            'code' => $diagnosis['code'],
                            'display' => $diagnosis['display']
                        ]
                    ]
                ];
            }
        }

        return $encounter;
    }

    /**
     * Mock ICD-10 data for development
     */
    private function getMockICD10Data(string $query, int $limit): array
    {
        $mockData = [
            [
                'resource' => [
                    'code' => 'A00',
                    'display' => 'Kolera',
                    'definition' => 'Infeksi akut usus halus yang disebabkan oleh Vibrio cholerae'
                ]
            ],
            [
                'resource' => [
                    'code' => 'A01',
                    'display' => 'Demam tifoid dan paratifoid',
                    'definition' => 'Penyakit infeksi sistemik yang disebabkan oleh Salmonella typhi'
                ]
            ],
            [
                'resource' => [
                    'code' => 'A02',
                    'display' => 'Infeksi Salmonella lainnya',
                    'definition' => 'Infeksi yang disebabkan oleh Salmonella selain Salmonella typhi'
                ]
            ],
            [
                'resource' => [
                    'code' => 'I10',
                    'display' => 'Hipertensi esensial (primer)',
                    'definition' => 'Tekanan darah tinggi tanpa penyebab yang dapat diidentifikasi'
                ]
            ],
            [
                'resource' => [
                    'code' => 'E11',
                    'display' => 'Diabetes mellitus tipe 2',
                    'definition' => 'Gangguan metabolisme karbohidrat dengan resistensi insulin relatif'
                ]
            ]
        ];

        // Filter by query
        $filtered = array_filter($mockData, function ($item) use ($query) {
            $code = $item['resource']['code'] ?? '';
            $display = $item['resource']['display'] ?? '';
            return stripos($display, $query) !== false || stripos($code, $query) !== false;
        });

        return array_slice(array_values($filtered), 0, $limit);
    }

    /**
     * Mock ICD-10 detail for development
     */
    private function getMockICD10Detail(string $code): array
    {
        $details = [
            'A00' => [
                'code' => 'A00',
                'display' => 'Kolera',
                'definition' => 'Infeksi akut usus halus yang disebabkan oleh Vibrio cholerae',
                'hierarchy' => 'Chapter I: Certain infectious and parasitic diseases (A00-B99)',
                'inclusion' => ['Kolera klasik', 'Kolera El Tor'],
                'exclusion' => ['Carrier kolera (Z22.0)']
            ],
            'I10' => [
                'code' => 'I10',
                'display' => 'Hipertensi esensial (primer)',
                'definition' => 'Tekanan darah tinggi tanpa penyebab yang dapat diidentifikasi',
                'hierarchy' => 'Chapter IX: Diseases of the circulatory system (I00-I99)',
                'inclusion' => ['Hipertensi benigna esensial', 'Hipertensi maligna esensial'],
                'exclusion' => ['Hipertensi sekunder (I15.-)']
            ],
            'E11' => [
                'code' => 'E11',
                'display' => 'Diabetes mellitus tipe 2',
                'definition' => 'Gangguan metabolisme karbohidrat dengan resistensi insulin relatif',
                'hierarchy' => 'Chapter IV: Endocrine, nutritional and metabolic diseases (E00-E90)',
                'inclusion' => ['Diabetes dengan ketoacidosis', 'Diabetes dengan komplikasi renal'],
                'exclusion' => ['Diabetes mellitus tipe 1 (E10.-)', 'Diabetes gestasional (O24.-)']
            ]
        ];

        return $details[$code] ?? [
            'code' => $code,
            'display' => 'Unknown diagnosis',
            'definition' => 'No detailed information available'
        ];
    }

    /**
     * Check if Satu Sehat is configured and available
     */
    public function isConfigured(): bool
    {
        return !empty($this->clientId) && !empty($this->clientSecret);
    }

    /**
     * Test connection to Satu Sehat API
     */
    public function testConnection(): bool
    {
        try {
            $this->getAccessToken();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
