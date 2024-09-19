<?php

class RTBBannerCampaign
{
    /**
     * @var mixed
     */
    private $bidRequest;
    /**
     * @var
     */
    private $campaigns;
    /**
     * @var string
     */
    private $logFile;

    public function __construct($bidRequestJson, $campaignsArray)
    {
        $this->bidRequest = json_decode($bidRequestJson, true);
        $this->campaigns = $campaignsArray;
        $this->logFile = __DIR__ . '/log.txt';
        $this->log('info', "RTBBannerCampaign initialized with bidRequest: " . json_encode($this->bidRequest));
    }

    /**
     * @return string
     */
    public function handleBidRequest(): string
    {
        $this->log('info', "Handling bid request: " . json_encode($this->bidRequest));

        if (!$this->validateBidRequest()) {
            $this->log('error', "Invalid bid request: " . json_encode($this->bidRequest));
            return json_encode(['error' => 'Invalid bid request']);
        }

        $selectedCampaign = $this->selectCampaign();

        if ($selectedCampaign) {
            $this->log('info', "Selected campaign: " . json_encode($selectedCampaign));
            return $this->generateResponse($selectedCampaign);
        } else {
            $this->log('warning', "No suitable campaign found for bid request: " . json_encode($this->bidRequest));
            return json_encode(['error' => 'No suitable campaign found']);
        }
    }

    /**
     * @return bool
     */
    private function validateBidRequest(): bool
    {
        $isValid = isset($this->bidRequest['id'], $this->bidRequest['imp'], $this->bidRequest['device'], $this->bidRequest['app']);
        $this->log('info', "Bid request validation result: " . ($isValid ? "valid" : "invalid"));
        return $isValid;
    }

    /**
     * @return array|null
     */
    private function selectCampaign(): ?array
    {
        $bestCampaign = null;
        $highestBid = 0;

        foreach ($this->campaigns as $campaign) {
            if ($this->isCampaignEligible($campaign)) {
                if ($campaign['price'] > $highestBid) {
                    $highestBid = $campaign['price'];
                    $bestCampaign = $campaign;
                }
            }
        }

        $this->log('info', "Best campaign selected: " . json_encode($bestCampaign));
        return $bestCampaign;
    }

    /**
     * @param $campaign
     * @return bool
     */
    private function isCampaignEligible($campaign): bool
    {
        $device = $this->bidRequest['device'];
        $geo = $device['geo'];
        $imp = $this->bidRequest['imp'][0];
        $banner = $imp['banner'];

        // Check device compatibility
        if (strpos($campaign['hs_os'], $device['os']) === false) {
            $this->log('info', "Campaign {$campaign['code']} is not eligible due to device OS mismatch.");
            return false;
        }

        // Check geographical targeting
        if ($campaign['country'] !== $geo['country']) {
            $this->log('info', "Campaign {$campaign['code']} is not eligible due to geographical mismatch.");
            return false;
        }

        // Check bid floor
        if ($campaign['price'] < $imp['bidfloor']) {
            $this->log('info', "Campaign {$campaign['code']} is not eligible due to bid floor.");
            return false;
        }

        // Check banner dimensions
        list($campaignWidth, $campaignHeight) = explode('x', $campaign['dimension']);
        if ($campaignWidth != $banner['w'] || $campaignHeight != $banner['h']) {
            $this->log('info', "Campaign {$campaign['code']} is not eligible due to banner dimensions mismatch.");
            return false;
        }

        return true;
    }

    /**
     * @param $campaign
     * @return string
     */
    private function generateResponse($campaign): string
    {
        $response = [
            'id' => $this->bidRequest['id'],
            'seatbid' => [
                [
                    'bid' => [
                        [
                            'id' => uniqid(),
                            'impid' => $this->bidRequest['imp'][0]['id'],
                            'price' => $campaign['price'],
                            'adid' => $campaign['code'],
                            'adm' => $campaign['htmltag'],
                            'crid' => $campaign['creative_id'],
                            'w' => explode('x', $campaign['dimension'])[0],
                            'h' => explode('x', $campaign['dimension'])[1],
                            'adomain' => [$campaign['tld']],
                            'iurl' => $campaign['image_url'],
                            'cid' => $campaign['appid'],
                            'attr' => [$campaign['attribute']],
                            'dealid' => $campaign['billing_id']
                        ]
                    ]
                ]
            ],
            'cur' => $this->bidRequest['cur'][0] ?? 'USD'
        ];

        $this->log('info', "Generated response: " . json_encode($response));
        return json_encode($response);
    }

    /**
     * @param $category
     * @param $message
     * @return void
     */
    public function log($category, $message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "$timestamp $category: $message" . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}