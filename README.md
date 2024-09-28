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

To run the unit tests I used PhpStorm run functionality.

##### CartItemTest

This class has only one test function. The test wasn't passing since the last assertion wasn't correct.

After analyzing how the "$modifier" parameter works on the "CartItem" class and the "is_available()" method, I started to get some doubts.

- The class CartItem suggests a product is available when the "expires" time is less than the current time, so time needs to pass to a product being available.
- The test case also suggests the same approach since the third object created is excepted to not being available for the first 59 seconds and supposed to be at 60 seconds.

> [!NOTE]
>
> Since both classes suggest the same, I assumed that the "CartItem" "$expires" variable defines the time that the product becomes available. In a real case scenario, I would try to talk to the team and know if I was right or not. My initial thoughts were that the "$expires" variable defined the exact time that the product becomes available.

Since the variable names didn't make much sense for the suggestions of the classes, I started to do some refactoring in the "CartItem" class.

- Added the missing break statement at "CartItem" class constructor.
- Changed "$expires" to "$available_at".
- Changed "available_at()" to "availableAt()" to follow PHP best practices.
- And "gestState()", "display()" and the template file to start using the new "$available_at" variable.

After these changes and after changing the test to use the new method name, the test passed.

##### CartTest

This class also has one single test. To make it pass, I only changed the "$expected" variable to use the new "avilableAt" format instead of "expires_at".

I used the JSON default convention to name the variable in the template section.

#### Functional tests

The single functional test presented was written with codeception. I never worked with codeception, but the test seems very intuitive.

I started by reading the [codeception documentation](https://codeception.com/docs/GettingStarted). When I tried to run the command "php vendor/bin/codecept run --steps" I got an error caused by the written tests because the "FunctionalTester" class was missing. After googling, I found a suggestion at [StackOverflow](https://stackoverflow.com/questions/36322580/symfony-codeception-run-errors) to run the "codecept build" command.

So after running the "php vendor/bin/codecept build" command, the file was generated, and I was able to run the test.

Firstly, the error was on the line "$I->see('Cart (0 items):');". I was not understanding what could be doing this, since when I accessed the index page I could see the expected tests. I then ran the test in debug mode "php vendor/bin/codecept run --steps --debug" and found that the tests weren't able to access the index page since the request was returning a 404 error.

After a quick analysis, I found that the problem was with the test configuration .yml file, the configuration was intended to use "http://127.0.0.1:80" as the default application url, since I was serving the application through port 8000 instead of 80, I changed the configuration file.

After that, the simulate session part of the tests was causing the test to fail, so I copied the [url](http://localhost:8000/?items=[{"price"%3A123%2C"expires"%3A"never"}%2C+{"price"%3A200%2C"expires"%3A"60min"}]) to the browser to figure out the problem. The error was on the "Cart" constructor, I changed the constructor in order to work. I had to do several changes in this class since the "$modifier" was not taken into consideration, so I developed two functions, one to extract the unit and value of the expired/available creation timestamp for the CartItem, I used StackOverflow to get and idea on how to extract the numbers/letters from the "$value" variable.

And after that, I added an if statement on the template file of the CartItem to be able to show different messages in case the item mode is MODE_NO_LIMIT.

### Installation

The instructions to install the project are the following:

1. Ensure that composer is globally available on the machine.
2. Ensure that PHP version 7.4. is in use.
3. Install composer dependencies: "composer install".
4. Start the application by running the command "php -S localhost:8000 -t public", the application can be accessed through http://localhost:8000/.

### Running the tests

To run the unit tests, there were 2 possibilities.

- They can be run directly in the IDE by configuring the tests runnable engine and pressing play in each test or folder that we want to test.

- Run the command "vendor/bin/phpunit tests/unit" to run all the unit tests.

To run the functional tests, it is needed to run:

1. "php vendor/bin/codecept build" This command builds the classes required to run the tests in the current project installation; this command is only needed for the first time.
2. "php vendor/bin/codecept run —steps" to run the tests. The command "php vendor/bin/codecept run --steps --debug" can be run if we want a more accurate debug analysis.
