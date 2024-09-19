# Real-Time Bidding System

This project implements a simple real-time bidding (RTB) system for banner campaigns. It includes three main files:

1. `index.php`
2. `RTBBannerCampaign.php`
3. `RTBBannerCampaignTest.php`

## Files Overview

### 1. `index.php`

This is the entry point of the application. It includes the necessary files and runs the tests.

### 2. `RTBBannerCampaign.php`

This file contains the `RTBBannerCampaign` class, which handles the bid requests and selects the appropriate campaign based on the bid request parameters.

#### Key Methods:

- `__construct($bidRequestJson, $campaignsArray)`: Initializes the class with the bid request and campaigns array.
- `handleBidRequest()`: Handles the bid request and returns a response.
- `validateBidRequest()`: Validates the bid request.
- `selectCampaign()`: Selects the best campaign based on the bid request.
- `isCampaignEligible($campaign)`: Checks if a campaign is eligible based on the bid request parameters.
- `generateResponse($campaign)`: Generates the response for the selected campaign.

### 3. `RTBBannerCampaignTest.php`

This file contains the `RTBBannerCampaignTest` class, which runs various tests on the `RTBBannerCampaign` class.

#### Key Methods:

- `runTests()`: Runs all the tests.
- `testValidBidRequest()`: Tests a valid bid request.
- `testInvalidBidRequest()`: Tests an invalid bid request.
- `testNoSuitableCampaign()`: Tests a bid request with no suitable campaign.
- `testCampaignSelection()`: Tests the campaign selection process.



## How to Run

1. Ensure you have PHP installed on your system.
2. Place the three files (`index.php`, `RTBBannerCampaign.php`, `RTBBannerCampaignTest.php`) in the same directory.
3. Create the necessary JSON files (`bid_request.json` and `invalid_bid_request.json`) in the same directory.
4. Run the `index.php` file using the PHP CLI or a web server.



This will execute the tests and print the results to the console.

## License
NONE