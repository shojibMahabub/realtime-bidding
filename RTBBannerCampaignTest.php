<?php


class RTBBannerCampaignTest
{

    private $campaignsArray = [
        [
            "campaignname" => "Test_Banner_13th-31st_march_Developer",
            "advertiser" => "TestGP",
            "code" => "118965F12BE33FB7E",
            "appid" => "20240313103027",
            "tld" => "https://adplaytechnology.com/",
            "portalname" => "",
            "creative_type" => "1",
            "creative_id" => 167629,
            "day_capping" => 0,
            "dimension" => "320x480",
            "attribute" => "rich-media",
            "url" => "https://adplaytechnology.com/",
            "billing_id" => "123456789",
            "price" => 0.1,
            "bidtype" => "CPM",
            "image_url" => "https://image.png",
            "htmltag" => "",
            "from_hour" => "0",
            "to_hour" => "23",
            "hs_os" => "Android,iOS,Desktop",
            "operator" => "Banglalink,GrameenPhone,Robi,Teletalk,Airtel,Wi-Fi",
            "device_make" => "No Filter",
            "country" => "Bangladesh",
            "city" => "",
            "lat" => "",
            "lng" => "",
            "app_name" => null,
            "user_list_id" => "0",
            "adplay_logo" => 1,
            "vast_video_duration" => null,
            "logo_placement" => 1,
            "hs_model" => null,
            "is_rewarded_inventory" => 0,
            "pixel_tag" => null,
            "dmp_campaign_audience" => 0,
            "platform" => null,
            "open_publisher" => 1,
            "audience_targeting" => 0,
            "native_title" => null,
            "native_type" => null,
            "native_data_value" => null,
            "native_data_cta" => null,
            "native_data_rating" => null,
            "native_data_price" => null,
            "native_img_icon" => null
        ],
        [
            "campaignname" => "Test_Banner_13th-31st_march_Developer",
            "advertiser" => "TestGP",
            "code" => "118965F12BE33FB7E",
            "appid" => "20240313103027",
            "tld" => "https://adplaytechnology.com/",
            "portalname" => "",
            "creative_type" => "1",
            "creative_id" => 167629,
            "day_capping" => 0,
            "dimension" => "320x50",
            "attribute" => "rich-media",
            "url" => "https://adplaytechnology.com/",
            "billing_id" => "123456789",
            "price" => 0.1,
            "bidtype" => "CPM",
            "image_url" => "https://image.png",
            "htmltag" => "",
            "from_hour" => "0",
            "to_hour" => "23",
            "hs_os" => "Android,iOS,Desktop",
            "operator" => "Banglalink,GrameenPhone,Robi,Teletalk,Airtel,Wi-Fi",
            "device_make" => "No Filter","country" => "Bangladesh",
            "city" => "",
            "lat" => "",
            "lng" => "",
            "app_name" => null,
            "user_list_id" => "0",
            "adplay_logo" => 1,
            "vast_video_duration" => null,
            "logo_placement" => 1,
            "hs_model" => null,
            "is_rewarded_inventory" => 0,
            "pixel_tag" => null,
            "dmp_campaign_audience" => 0,
            "platform" => null,
            "open_publisher" => 1,
            "audience_targeting" => 0,
            "native_title" => null,
            "native_type" => null,
            "native_data_value" => null,
            "native_data_cta" => null,
            "native_data_rating" => null,
            "native_data_price" => null,
            "native_img_icon" => null
        ],
    ];

    public function runTests()
    {
        $this->testValidBidRequest();
        $this->testInvalidBidRequest();
        $this->testNoSuitableCampaign();
        $this->testCampaignSelection();
    }

    private function testValidBidRequest()
    {
        $bidRequestJson = file_get_contents('bid_request.json');
        
        $rtb = new RTBBannerCampaign($bidRequestJson, $this->campaignsArray);
        $response = $rtb->handleBidRequest();
        $rtb->log("info","Test Valid Bid Request: $response");
    }

    private function testInvalidBidRequest()
    {
        $bidRequestJson = file_get_contents('invalid_bid_request.json');

        $rtb = new RTBBannerCampaign($bidRequestJson, $this->campaignsArray);
        $response = $rtb->handleBidRequest();
        $rtb->log("error", "Test Valid Bid Request: $response");
    }

    private function testNoSuitableCampaign()
    {
        $bidRequestJson = file_get_contents('bid_request.json');

        $rtb = new RTBBannerCampaign($bidRequestJson, []);
        $response = $rtb->handleBidRequest();
        $rtb->log("warning", "Test Valid Bid Request: $response");
    }

    private function testCampaignSelection()
    {
        $bidRequestJson = file_get_contents('bid_request.json');

        $rtb = new RTBBannerCampaign($bidRequestJson, $this->campaignsArray);
        $response = $rtb->handleBidRequest();
        $rtb->log("info", "Test Valid Bid Request: $response");
    }
}