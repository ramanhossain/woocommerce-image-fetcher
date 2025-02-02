Table of Contents<BR>
Installation<BR>
Configuration<BR>
Usage<BR>
Troubleshooting<BR>
License<BR>
<BR>
Installation<BR>
Step 1: Download the Plugin<BR>
Clone or download this repository to your local machine or server.<BR>
If downloaded as a ZIP file, extract it to a folder.<BR>
Step 2: Upload the Plugin to WordPress<BR>
Log in to your WordPress Admin Dashboard.<BR>
Go to Plugins > Add New.<BR>
Click on Upload Plugin.<BR>
Select the plugin .zip file or upload the folder directly via FTP to the /wp-content/plugins/ directory.<BR>
After uploading, click Install Now and then activate the plugin.<BR>
Configuration<BR>
Once the plugin is activated, you will need to configure it with your WooCommerce and Google API credentials.<BR>
<BR>
Step 1: Go to Plugin Settings<BR>
In the WordPress Admin Dashboard, navigate to Image Fetcher in the left sidebar.<BR>
Click on Settings to open the settings page.<BR>
Step 2: Fill in API Details<BR>
In the settings page, you will need to fill out the following fields:<BR>
<BR>
WooCommerce API URL: The URL for your WooCommerce store's API (usually in the format https://yourstore.com/wp-json/wc/v3/).<BR>
Consumer Key: The WooCommerce API Consumer Key.<BR>
Consumer Secret: The WooCommerce API Consumer Secret.<BR>
Google API Key: The API key for Google Custom Search (you can generate it via the Google Developers Console).<BR>
Google Search Engine ID: The Custom Search Engine ID associated with your Google API.<BR>
Once these fields are filled out, click Save Changes to store the settings.<BR>
<BR>
Usage<BR>
Step 1: View Products Without Images<BR>
Go to Image Fetcher > Product List in the WordPress dashboard.<BR>
A table will appear listing products that are missing images. The table will display the product ID and the EAN (barcode) number for each product.<BR>
Step 2: Fetch and Add Images<BR>
Click the Fetch and Add Images button.<BR>
The plugin will use the EAN (barcode) number for each product to search for an image using the Google Custom Search API.<BR>
If an image is found, it will be automatically added to the product in WooCommerce.<BR>
You will see the number of successful updates, and a list of updated products will be displayed along with their EAN numbers.<BR>
<BR>
Troubleshooting<BR>
Missing API Credentials<BR>
Ensure that you have correctly entered the WooCommerce API URL, Consumer Key, Consumer Secret, Google API Key, and Google Search Engine ID in the plugin settings page.<BR>
<BR>
No Images Found<BR>
Verify that the EAN number is correctly set in the product meta data.<BR>
Make sure the Google Custom Search API is properly configured and that the Search Engine ID is correct.<BR>
If the issue persists, check the API response by inspecting the network requests in your browser’s developer tools.<BR>
Plugin Not Working After Activation<BR>
Clear your browser cache and refresh the WordPress dashboard.<BR>
Ensure your WordPress and WooCommerce versions are compatible with the plugin.<BR>
Check the server error logs to identify any issues.<BR>
https://programmablesearchengine.google.com/controlpanel/all <BR>
<BR>
<BR>
<BR>
License<BR>
This plugin is licensed under the MIT License.<BR>
<BR>
Feel free to contribute to this project or report any issues via the Issues section of this GitHub repository.<BR>

This README provides a comprehensive guide for installing, configuring, and using the WooCommerce Image Fetcher plugin. If you need further assistance, please open an issue in the GitHub repository, and we'll be happy to help!
