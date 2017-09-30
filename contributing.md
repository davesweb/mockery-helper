# Contributing

First of all, thank you for taking the time to make this project even better and more awesome 
than it already is!

That being said, there are a few rules and guidelines to obey to. First of all, read 
[the code of conduct](https://www.contributor-covenant.org/version/1/4/code-of-conduct/) 
carefully and abide by it.

We use [this branching strategy](http://nvie.com/posts/a-successful-git-branching-model/)

## Branch naming conventions

Try to use a descriptive name for your branch, but keep it below 120 characters.

For new features, use the `feature/` namespace. For instance: `feature/my-awesome-feature`.
For bugfixes, use the `bugfix/` namespace. For instance `bugfix/fix-some-error-in-some-class`.
For hotfixes, use the `hotfix/` namespace. For instance `hotfix/fix-some-critical-error`.

## Pull requests

Once you're satisfied with your code, open a pull request targeting the correct branch. As soon 
as you've done so we will inspect and test your code. You may get some feedback, which you should 
address. Once your pull request is approved it'll be merged into the appropriate branch by us.

Your code should of course do what it's intended to do and not break any existing code. Your 
code should be written according to the coding standards 
[PSR-1](http://www.php-fig.org/psr/psr-1/) and [PSR-2](http://www.php-fig.org/psr/psr-2/). 
Besides the coding standards, your code should follow these rules:

* All code must be Unit tested. Test cases must be logical and cover all possible scenario's.
* All code should be fixed by PHP-CS-Fixer with the .php-cs rules provided in this package.
* All branches should follow the correct branch naming strategy.
* Before submitting your pull request, make sure to run *all* unit tests to make sure no existing functionality broke.
* Use a descriptive commit message. Use [this guide](https://chris.beams.io/posts/git-commit/) for help.