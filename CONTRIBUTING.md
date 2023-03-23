# Contribution Guide

How to contribute to the project ?

1. Fork the Github directory of the project

2. Clone locally your fork
`git clone https://github.com/YourUsername/Openclassrooms-Project-08-ToDo-List.git`

3. Install the project with its dependences [see instructions](../README.md)

5. Create a new git branch
`git checkout -b new-branch`

6. Push it on your fork
`git push origin new-branch`

7. Open a pull request on the project's Github directory

# Quality process

We strongly recommend using a linter and analysing your code to help you detect bugs and error before pushing your contribution. We used [PHPCSFixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) and [PHPStan](https://phpstan.org/user-guide/getting-started) (from level 0 to 9) but you can use any tools of your liking.

After this run the tests with generation of a code coverage report:
`vendor/bin/phpunit --coverage-html public/test-coverage`.

To implement new tests, refer to the [documentation officielle de Symfony](https://symfony.com/doc/current/testing.html)