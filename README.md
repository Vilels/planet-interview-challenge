# Planet interview challenge

**Picture the scene:** you're working your first day at the new job, all eager to get to work with all these shiny new technologies that make development a breeze and improve the quality of life. And then you are handed with your first assignment.
But the project that you open is not the company's biggest, brightest, most important project.
It's not even the second most important one.
With rising dread, the dawning of an understanding building deep inside your mind finally gives form to those two words, the words most abhorred by all programmers, software developers, coders, hackers and testers out in the world:

Legacy system.

You've been handed a legacy system.
A summary briefing takes place, which actually doesn't take long since all they can tell you is that the previous person working it was some guy called Gurd and nobody has any idea when Gurd actually even resigned. The handover is over in almost an instant.

You are left with simple instructions: "Make the tests pass, and provide a tester with instructions on how to install it and run the tests. I don't care what you do or how you do it, **as long as the tests pass.** Also, if you want to demonstrate your mastery over PHP and legacy projects, go ahead and make the project more presentable and up-to-date - **as long as the tests pass.**"

## Further instructions
- Fork this repository, and push all the changes that you make to your forked copy.
- The instructions for how to run the app and the tests should be added to the repository's README.md file (this one).
- **Important:** the file README.md **must** also describe which parts of your solution were not made from scratch - this includes but not limited to usign generative AI such as ChatGPT, copying code from StackOverflow or other online resource or using any other existing resources such as Google search results. If the entirety of your submission was made from the ground up by yourself, please state this as well.
- Once finished, reach out to your recruitment contact in Planet to let them know you have finished the assignment.

## Resolution

In this part of the md file, I will try to express my thoughts while I'm working on the given exercise.

First of all, I will create a plan to track the objectives I want to achieve.

**Plan:**
- **Analyze the project:** identifying the structure, core concepts, and main files of the project. 
- **Set up the project:** Install the project on my machine and all its dependencies. 
- **Fix the tests:** Changing the tests and the related application code for making the tests pass. 
- **Refactoring:** Trying to identify classes or functions where the code can be refactored, making it more presentable or up-to-date. 
- **Documentation:** Adding instructions on how to run the application and tests.

### Project resume

Firstly I start the exercise by making a quick analysis of the project with the objective to gain a context and identifying the flow.

1. **PHP Version**: 
   - By looking for the composer.json I identify that the project is supposed to run in PHP 7.4 version.
2. **Main Entry Point**: 
   - The public/index.php file does the autoload for the composer dependencies and runs the application by using the run() method of the \Planet\InterviewChallenge\App class.
3. **Application class** - \Planet\InterviewChallenge\App.php:
   - This class starts by setting up the smarty template engine.
   - Registers a custom plugin related to date formats.
   - Starts the php output buffering by runing the "ob_start()".
   - Assigns a "ShopCart" variable to smartie template engine.
   - Renders the "App.tpl" template file with smartie.
4. **Application template** - \Planet\InterviewChallenge\App.tpl:
   - This file represents the template used by the application class, it uses the "ShopCart" variable to display the shoping cart content.
5. **Shop Classes** - src\Shop (folder):
   1. This folder includes the core models for the application including:
      1. Planet\InterviewChallenge\Shop\Cart.php
         - Represents a shop cart containing a list of items.
      2. Planet\InterviewChallenge\Shop\CartItem.php
         - Represents a shop cart item that have a price, expiration time and can be definend acording to diffrent modes.
6. **Shop Template** - src\tpl\shop (folder):
   - Contains template files for both models identified above.
7. **Tests**: - tests (folder)
   - This folder contains the functional and unit tests that needed to pass.
   - It also contains the bootstrap files for the test cases.

### Project set up

- I started by trying to install the composer dependencies by running "composer install" wile using the PHP 7.4 version.
  - I wasn't able to install the dependencies since the package "intervention/gif": "^4.1" required the version 8.1 of PHP [link](https://github.com/Intervention/gif/tree/4.1.0?tab=readme-ov-file#requirements) 
  - The solution for this was to remove the package since after a fast analysis I could't found any package references/uses across the application.
- After instalating the dependencies, I started the application with the command "php -S localhost:8000 -t public".
- By acessing the localhost url, I founded that the class "src/Shop/Cart.php" is using a deprecrated function "each()" so I changed to start using the "foreach()" instead.

### Fixing the tests

#### Unit tests

##### CartItemTest
