=== YOP Poll ===
Contributors: yourownprogrammer
Donate Link: http://www.yourownprogrammer.com/thankyou/bee.php
Tags: poll, polls, vote, survey, polling, yop poll, yop
Requires at least: 3.3
Tested up to: 3.6
Stable tag: trunk
License: GPLv2 or later

Use a full option polling functionality to get the answers you need.           

YOP Poll is the perfect, easy to use poll plugin for your WP site.


== Description ==

YOP Poll is a truly professional and complete tool for your online polls. 

It offers all the basic features included in any other poll plugin but the improvements we've added and the fact that it's fully customizable really make it special. 

The concept of the plugin revolves around user experience - it's very intuitive and easy to use and it was designed with the user in mind. We wanted to offer you the best and make sure that every detail was considered.

Over the past months we have constantly improved it and we added many of the suggestions received from our users just to make sure that they really get the plugin they need.

It is easy to use, even for someone who doesn't have any experience as a programmer or developer. However, if you have any questions or suggestions don't hesitate to contact us. We're here to help. We want to ensure that you get the best out of this plugin and any feedback is welcomed.

Some of the features included that you can customize are: poll scheduling, displaying polls, poll answers, poll results, new custom fields, archive, display and many, many others.

This plugin is the result of months of hard work - the original idea, then the technical aspects and weeks of testing and improvements to be sure that it's reliable and it does exactly what you want, when you want it.


== Installation ==

1. Upload 'plugin-name.php' to the '/wp-content/plugins/' directory,
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

This plugin was especially designed for flexibility and it is very easy to use. We don't expect you to encounter serious issues, but we included a list with some logical questions that you may find useful.

1.  How can I create a poll?        

	*  Go to your YOP Poll menu and select the "Add New" option.    

	*  Fill the required information according to the examples we included: name, question, answers (add additional ones if you need), select the start/end date for your poll, and decide on the advanced settings for results, votes, voters, accessibility etc.   

	*  Once you decided on all your poll details, click on "Save".   

	*  To view your new poll access "All Polls" from your main menu and choose the corresponding entry from the list.   


2.  How can I link a poll to my webpage?     

	*  Find out the ID assigned to poll by accessing "All Polls".   
	   Locate your poll and notice the ID on the left, before the name section.   

	*  Copy the following shortcode and paste it in your page: [yop_poll id="ID"]   
           For instance, if the poll you want to display has the ID=15 the code will be: [yop_poll id="15"].   

	*  This is it. Check your page or post now.   


3.  Do you have some predefined shortcodes that I can use?    

	Yes.       

	Current Active Poll ID = -1:   [yop_poll id="-1"]      

	Latest Poll id = -2:           [yop_poll id="-2"]      

	Random Poll id = -3:           [yop_poll id="-3"]     
	

4.  Can I have more than one poll active?      

	Yes, you can run multiple polls at the same time or you can schedule them to begin one after another using the "Select start/end date" option.       


5.  Can I ask for additional information from my voters?       

	Yes, you can customize your poll to request additional information. Eg. name, email, age, profession. To include this, when you create your poll using the "Add New" form, go to "Custom Text Fields" -> "Add new custom field" and include as many requests as you need.   


6.  How can I create/modify a template?      

	*  Access the "Templates" menu.     
	
	*  If you want to create a new template use the "Add new" option and include the corresponding HTML/visual code.    

	*  If you want to modify an existing template, select it from the Templates list and choose "Edit". You will access the HTML/visual code you want to edit.     


7.  How do I check the results?      

	*  Locate the poll you want to evaluate by accessing "All Polls".     
	
	*  Below the name of the poll you have several options.       

	*  Use the "Results" link to track the results of the poll,      

	*  or access the "Logs" for a more detailed evaluation. 


8.  What is the difference between Options and Poll Options for each poll?      

	*  Options (located under plugin menu) is the way to specifify general settings for all your polls.     
	
	*  If you want to go further and customize each poll, these settings will take precedence over Options settings.         


9.  How can I edit access to YOP Poll for administrators, editors, authors?      

	*  To do this, in your wordpress go to Plugins->Editor.     
	
	*  On the right choose Yop Poll as the plugin to be edited.

	*  The file you need to edit is yop-poll/inc/admin.php.

	*  The file you need to edit is yop-poll/inc/admin.php.

	*  Once you open the file, do a search for function current_user_can.

	*  In that function you can find the options you need to edit.       


10.  How can I see the results after the poll ends?      

	*  Edit your poll and in "View Results:" choose "After Poll End Date" and save.       


11.  How can I add a Poll Archive page on my website?      

	*  From your WordPress menu create a new page that contains [yop_poll_archive] and has the permalink http://www.yourwebsite.com/polls/       


12.  How can I add a hyperlink in the poll question or add a photo as an answer?      

	*  To add a link to your question you can use <a href="[your link]" target="_blank">[link text]</a>       

	*  to add a photo as an answer you can use <img src=[photo_url] title="[photo_title]" alt="[photo_description]"/>       


11.  Can I add more than one question to a poll?      

	*  You can have only one question per poll. If you want to ask more than one question, you have to create a poll for each one.       





== Screenshots ==


1. Add New
2. Templates
3. View All
4. YOP Poll as a widget with a custom field defined
5. YOP Poll on a page with a custom field defined


== Changelog ==

= 4.5 =

* Added ability to choose date format when displaying polls
* Added ability to limit viewing results only for logged in users
* Added ability to add custom answers to poll answers
* Added new shortcode [yop_poll id="-4"] that displays latest closed poll
* Added an offset for shortcodes. [yop_poll id="-1" offset="0"] displays the first active poll found, [yop_poll id="-1" offset="1"] displays the second one.
* Added WPML compatibility
* Various bugs fixes

= 4.4 =

* Added ability to reset polls
* Added ability to to add a custom message to be displayed after voting
* Added ability to allow users to vote multiple times on the same poll
* Various bugs fixes

= 4.3 =

* Added multisite support
* Added ability to redirect to a custom url after voting
* Added ability to edit polls and templates author
* Added ability to set a response as default
* Improvements on View Results
* Added ability to edit number of votes (very usefull when migrating polls)
* Added tracking capabilities
* Various improvements on logs


= 4.2 =

* Added captcha
* Fixed issue with start date and end date when adding/editing a poll
* Fixed issue with the message displayed when editing a poll

= 4.1 =

* Fixed js issue causing the widget poll not to work

= 4.0 =

* Added ability to use custom loading animation. 
* Added capabilities and roles
* Fixed issue with update overwritting settings

= 3.9 = 

* Fixed display issue with IE7 and IE8

= 3.8 = 

* Fixed compatibility issue with Restore jQuery plugin
* Added ability to link poll answers

= 3.7 = 

* Fixed issue with Loading text displayed above the polls
* Fixed issue with deleting answers from polls

= 3.6 = 

* Fixed issue with missing files

= 3.5 = 

* Added french language pack
* Added loading animation when vote button is clicked
* Fixed issue with characters encoding

= 3.4 = 

* Fixed issue with menu items in admin area
* Fixed issue with language packs

= 3.3 = 

* Added option to auto generate a page when a poll is created
* Fixed compatibility issues with IE
* Fixed issues with custom fields

= 3.2 = 
* Fixed bug that was causing issues with TinyMCE Editor

= 3.1 = 
* Various bugs fixed

= 3.0 = 
* Added export ability for logs
* Added date filter option for logs
* Added option to view logs grouped by vote or by answer
* Various bugs fixed

= 2.0 = 
* Fixed various bugs with templates

= 1.9 = 
* Fixed various bugs with templates

= 1.8 = 
* Fixed bug with wordpress editor

= 1.7 = 
* Fixed bug that was causing poll not to update it's settings

= 1.6 = 
* Added ability to change the text for Vote button   
* Added ability to display the answers for Others field

= 1.5 = 
* Fixed sort_answers_by_votes_asc_callback() bug

= 1.4 = 
* Fixed compatibility issues with other plugins

= 1.3 = 
* Fixed bug that was causing widgets text not to display

= 1.2 =  
* Fixed do_shortcode() with missing argument bug

= 1.1 =   
* Fixed call_user_func_array() bug   



== Donations ==  

We've given a lot of thought to make this application one of the best ones available and we continue to invest our time and effort perfecting it. If you want to support our work, please consider making a donation.