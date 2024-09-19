<?php


class RTBBannerCampaignTest
{
    public function runTests()
    {
        $this->testValidBidRequest();
        $this->testInvalidBidRequest();
        $this->testNoSuitableCampaign();
        $this->testCampaignSelection();
    }

    private function testValidBidRequest()
    {
        $bidRequestJson = json_encode([
            'id' => '1',
            'imp' => [['id' => '1', 'bidfloor' => 0.5, 'banner' => ['w' => 300, 'h' => 250]]],
            'device' => ['os' => 'iOS', 'geo' => ['country' => 'USA']],
            'app' => ['id' => 'app1'],
            'cur' => ['USD']
        ]);

        $campaignsArray = [
            [
                'price' => 1.0,
                'hs_os' => 'iOS',
                'country' => 'USA',
                'dimension' => '300x250',
                'code' => 'ad1',
                'htmltag' => '<div>Ad</div>',
                'creative_id' => 'creative1',
                'tld' => 'example.com',
                'image_url' => 'http://example.com/ad.jpg',
                'appid' => 'app1',
                'attribute' => 'attr1',
                'billing_id' => 'deal1'
            ]
        ];

        $rtb = new RTBBannerCampaign($bidRequestJson, $campaignsArray);
        $response = $rtb->handleBidRequest();
        echo "Test Valid Bid Request: " . $response . "\n";
    }

    private function testInvalidBidRequest()
    {
        $bidRequestJson = json_encode([
            'id' => '1',
            'imp' => [['id' => '1', 'bidfloor' => 0.5, 'banner' => ['w' => 300, 'h' => 250]]],
            'device' => ['os' => 'iOS'],
            'app' => ['id' => 'app1'],
            'cur' => ['USD']
        ]);

        $campaignsArray = [];

        $rtb = new RTBBannerCampaign($bidRequestJson, $campaignsArray);
        $response = $rtb->handleBidRequest();
        echo "Test Invalid Bid Request: " . $response . "\n";
    }

    private function testNoSuitableCampaign()
    {
        $bidRequestJson = json_encode([
            'id' => '1',
            'imp' => [['id' => '1', 'bidfloor' => 0.5, 'banner' => ['w' => 300, 'h' => 250]]],
            'device' => ['os' => 'iOS', 'geo' => ['country' => 'USA']],
            'app' => ['id' => 'app1'],
            'cur' => ['USD']
        ]);

        $campaignsArray = [
            [
                'price' => 0.4,
                'hs_os' => 'iOS',
                'country' => 'USA',
                'dimension' => '300x250',
                'code' => 'ad1',
                'htmltag' => '<div>Ad</div>',
                'creative_id' => 'creative1',
                'tld' => 'example.com',
                'image_url' => 'http://example.com/ad.jpg',
                'appid' => 'app1',
                'attribute' => 'attr1',
                'billing_id' => 'deal1'
            ]
        ];

        $rtb = new RTBBannerCampaign($bidRequestJson, $campaignsArray);
        $response = $rtb->handleBidRequest();
        echo "Test No Suitable Campaign: " . $response . "\n";
    }

    private function testCampaignSelection()
    {
        $bidRequestJson = json_encode([
            'id' => '1',
            'imp' => [['id' => '1', 'bidfloor' => 0.5, 'banner' => ['w' => 300, 'h' => 250]]],
            'device' => ['os' => 'iOS', 'geo' => ['country' => 'USA']],
            'app' => ['id' => 'app1'],
            'cur' => ['USD']
        ]);

        $campaignsArray = [
            [
                'price' => 0.6,
                'hs_os' => 'iOS',
                'country' => 'USA',
                'dimension' => '300x250',
                'code' => 'ad1',
                'htmltag' => '<div>Ad</div>',
                'creative_id' => 'creative1',
                'tld' => 'example.com',
                'image_url' => 'http://example.com/ad.jpg',
                'appid' => 'app1',
                'attribute' => 'attr1',
                'billing_id' => 'deal1'
            ],
            [
                'price' => 1.0,
                'hs_os' => 'iOS',
                'country' => 'USA',
                'dimension' => '300x250',
                'code' => 'ad2',
                'htmltag' => '<div>Ad2</div>',
                'creative_id' => 'creative2',
                'tld' => 'example2.com',
                'image_url' => 'http://example2.com/ad2.jpg',
                'appid' => 'app2',
                'attribute' => 'attr2',
                'billing_id' => 'deal2'
            ]
        ];

        $rtb = new RTBBannerCampaign($bidRequestJson, $campaignsArray);
        $response = $rtb->handleBidRequest();
        echo "Test Campaign Selection: " . $response . "\n";
    }
}

$test = new RTBBannerCampaignTest();
$test->runTests();

?>