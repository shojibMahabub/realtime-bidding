<?php

class RTBBannerCampaign
{
    private $bidRequest;
    private $campaigns;

    public function __construct($bidRequestJson, $campaignsArray)
    {
        $this->bidRequest = json_decode($bidRequestJson, true);
        $this->campaigns = $campaignsArray;
    }

    public function handleBidRequest(): string
    {
        if (!$this->validateBidRequest()) {
            return json_encode(['error' => 'Invalid bid request']);
        }

        $selectedCampaign = $this->selectCampaign();

        if ($selectedCampaign) {
            return $this->generateResponse($selectedCampaign);
        } else {
            return json_encode(['error' => 'No suitable campaign found']);
        }
    }

    private function validateBidRequest(): bool
    {
        return isset($this->bidRequest['id'], $this->bidRequest['imp'], $this->bidRequest['device'], $this->bidRequest['app']);
    }

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

        return $bestCampaign;
    }

    private function isCampaignEligible($campaign): bool
    {
        $device = $this->bidRequest['device'];
        $geo = $device['geo'];
        $imp = $this->bidRequest['imp'][0];
        $banner = $imp['banner'];

        // Check device compatibility
        if (strpos($campaign['hs_os'], $device['os']) === false) {
            return false;
        }

        // Check geographical targeting
        if ($campaign['country'] !== $geo['country']) {
            return false;
        }

        // Check bid floor
        if ($campaign['price'] < $imp['bidfloor']) {
            return false;
        }

        // Check banner dimensions
        list($campaignWidth, $campaignHeight) = explode('x', $campaign['dimension']);
        if ($campaignWidth != $banner['w'] || $campaignHeight != $banner['h']) {
            return false;
        }

        return true;
    }

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

        return json_encode($response);
    }
}