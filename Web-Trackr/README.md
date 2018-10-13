# WebTrackr
PHP and MySQL - website for tracking a generic construction companies various components and allowing costumers to check information.

This project was designed to 'simulate' a contructions company's website, by which employee could add/update any information and customers soulc login to see thier own. Key features:
  - Bootstrap API - [w3school template was webpage](https://www.w3schools.com/bootstrap/bootstrap_templates.asp)
  - Access Validation
  - MySQL with PHP
  - Magic xD

# Screen shots
Login Page
![Imgur](http://i.imgur.com/QjsakNf.jpg?1)
Note: If either an employee or customer is logged in niether form will be displayed.

Customer's Home page
![Imgur](http://i.imgur.com/m5qQibE.jpg?1)
Displays their name with a welcome message, list of options, and the projects which belong to them. All subject categories of projects (Phase, Task, Transactions, etc) are all so limited.

Project Home Page (open to both customers and employees)
![Imgur](http://i.imgur.com/lwqLLVw.jpg?1)
Displays the current projects information and links to navigate to more detailed subcategory pages.

Customer Access Denied Example
![Imgur](http://i.imgur.com/9yYveCU.jpg?1)
If a customer manually goes to a page which he does not have access too (because we all know they try) they will be promted with an access denied and the back button to return to their home page.

Employee Home Page
![Imgur](http://i.imgur.com/ox18Yua.jpg?1)
Welcome message with their name, list of lists, and list of options for any operation they may preform.

Example of a list page - Project List
![Imgur](http://i.imgur.com/gPBDRhJ.jpg?1)
Displays all the projects with an update link so an employee can easily update the information.

Update Phase Example - Phase Update ( sorry it had the wrong title =p )
![Imgur](http://i.imgur.com/tcjlANk.jpg?1)
All the information is loaded to allow for easy editing of everything!

Sucess Message
![Imgur](http://i.imgur.com/TCnaEHR.jpg?1)
This is the sucess dialog which appeares after submitting a form filled out correctly. There are also error messages (they are bootstraps warning) which will alreat u to any error on MySQL's side. You may receive multiple success messages on add pages because a complete form allows you to link items together (adding a phase linking it to a project) as each component is broken down.
