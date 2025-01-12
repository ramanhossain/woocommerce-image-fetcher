Table of Contents
Installation
Configuration
Usage
Troubleshooting
License

Installation
Step 1: Download the Plugin
Clone or download this repository to your local machine or server.
If downloaded as a ZIP file, extract it to a folder.
Step 2: Upload the Plugin to WordPress
Log in to your WordPress Admin Dashboard.
Go to Plugins > Add New.
Click on Upload Plugin.
Select the plugin .zip file or upload the folder directly via FTP to the /wp-content/plugins/ directory.
After uploading, click Install Now and then activate the plugin.
Configuration
Once the plugin is activated, you will need to configure it with your WooCommerce and Google API credentials.

Step 1: Go to Plugin Settings
In the WordPress Admin Dashboard, navigate to Image Fetcher in the left sidebar.
Click on Settings to open the settings page.
Step 2: Fill in API Details
In the settings page, you will need to fill out the following fields:

WooCommerce API URL: The URL for your WooCommerce store's API (usually in the format https://yourstore.com/wp-json/wc/v3/).
Consumer Key: The WooCommerce API Consumer Key.
Consumer Secret: The WooCommerce API Consumer Secret.
Google API Key: The API key for Google Custom Search (you can generate it via the Google Developers Console).
Google Search Engine ID: The Custom Search Engine ID associated with your Google API.
Once these fields are filled out, click Save Changes to store the settings.

Usage
Step 1: View Products Without Images
Go to Image Fetcher > Product List in the WordPress dashboard.
A table will appear listing products that are missing images. The table will display the product ID and the EAN (barcode) number for each product.
Step 2: Fetch and Add Images
Click the Fetch and Add Images button.
The plugin will use the EAN (barcode) number for each product to search for an image using the Google Custom Search API.
If an image is found, it will be automatically added to the product in WooCommerce.
You will see the number of successful updates, and a list of updated products will be displayed along with their EAN numbers.

Troubleshooting
Missing API Credentials
Ensure that you have correctly entered the WooCommerce API URL, Consumer Key, Consumer Secret, Google API Key, and Google Search Engine ID in the plugin settings page.

No Images Found
Verify that the EAN number is correctly set in the product meta data.
Make sure the Google Custom Search API is properly configured and that the Search Engine ID is correct.
If the issue persists, check the API response by inspecting the network requests in your browser’s developer tools.
Plugin Not Working After Activation
Clear your browser cache and refresh the WordPress dashboard.
Ensure your WordPress and WooCommerce versions are compatible with the plugin.
Check the server error logs to identify any issues.
License
This plugin is licensed under the MIT License.

Feel free to contribute to this project or report any issues via the Issues section of this GitHub repository.

This README provides a comprehensive guide for installing, configuring, and using the WooCommerce Image Fetcher plugin. If you need further assistance, please open an issue in the GitHub repository, and we'll be happy to help!
