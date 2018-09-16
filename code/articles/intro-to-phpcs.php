<article>
    <h2>Intro to PHP_CodeSniffer (phpcs)</h2>

    <p>
        PHP_CodeSniffer (phpcs) is a command-line tool for checking PHP coding standards.
        Coding standards are rules for whitespace, variable names, etc. to give a codebase a consistent format for readability.
        When the rules are checked automatically, it allows developers to focus on other aspects of coding and allows code reviewers to provide more meaningful feedback.
    </p>

    <p>
        <a href="https://github.com/squizlabs/PHP_CodeSniffer">PHP_CodeSniffer</a> can check for
        <a href="https://pear.php.net/manual/en/standards.php">PEAR</a>,
        <a href="https://www.php-fig.org/psr/psr-1">PSR1</a>,
        <a href="https://www.php-fig.org/psr/psr-2">PSR2</a>,
        <a href="https://www.php-fig.org/psr/psr-12">PSR12</a>,
        and <a href="https://framework.zend.com/manual/2.4/en/ref/coding.standard.html">Zend</a> standards.
        It can also check for the Squiz standard, which applies to PHP, HTML, and JS files.
    </p>

    <p>
        Let's see phpcs in action. Here is some code with several violations of the PSR-2 standard and the phpcs report:

        <pre>
&lt;?php

class myClass {
    function helloWorld()
    {
    echo 'hello world';
    }
}
        </pre>

        <pre>
$ ./phpcs --standard=PSR2 /code/MyClass.php 

FILE: /code/MyClass.php
---------------------------------------------------------------------------------------------------
FOUND 5 ERRORS AFFECTING 3 LINES
---------------------------------------------------------------------------------------------------
 3 | ERROR | [ ] Each class must be in a namespace of at least one level (a top-level vendor name)
 3 | ERROR | [ ] Class name "myClass" is not in PascalCase format
 3 | ERROR | [x] Opening brace of a class must be on the line after the definition
 4 | ERROR | [ ] Visibility must be declared on method "helloWorld"
 6 | ERROR | [x] Line indented incorrectly; expected at least 8 spaces, found 4
---------------------------------------------------------------------------------------------------
PHPCBF CAN FIX THE 2 MARKED SNIFF VIOLATIONS AUTOMATICALLY
---------------------------------------------------------------------------------------------------

Time: 63ms; Memory: 6Mb
        </pre>
    </p>

    <p>
        PHP_CodeSniffer found 5 separate issues and provided a line number and description for each.
    </p>

    <h3>Fixing code style automatically</h3>

    <p>
        PHP_CodeSniffer is accompanied by another tool called PHP Code Beautifier and Fixer (phpcbf), which can automatically fix some of the issues identified by phpcs.
        In the report above, 2 of the 5 errors (identified by "[x]") can be fixed automatically.
    </p>

    <p>
        Let's see it in action:
        <pre>
./phpcbf --standard=PSR2 /code/MyClass.php 

PHPCBF RESULT SUMMARY
--------------------------------------------------------------------------
FILE                                                      FIXED  REMAINING
--------------------------------------------------------------------------
/code/MyClass.php                                         2      3
--------------------------------------------------------------------------
A TOTAL OF 2 ERRORS WERE FIXED IN 1 FILE
--------------------------------------------------------------------------

Time: 41ms; Memory: 6Mb

        </pre>

        <pre>
$ cat /code/MyClass.php 
&lt;?php

class myClass
{
    function helloWorld()
    {
        echo 'hello world';
    }
}
        </pre>
    </p>

    <h3>Configuration</h3>

    <p>
        CodeSniffer can be configured to process certain files or source code from stdin.
        It can also be configured to output the report with colors and process files in parallel for performance, among other options.
        These options are covered <a href="https://github.com/squizlabs/PHP_CodeSniffer/wiki/Usage">here</a>.
    </p>

    <p>
        Some code standards can also be configured.
        For example, there is a configuration option to expect Windows-style newlines instead of *nix style newlines.
        These options are covered <a href="https://github.com/squizlabs/PHP_CodeSniffer/wiki/Customisable-Sniff-Properties">here</a>.
    </p>

    <h3>Custom code styles and PHP_CodeSniffer</h3>

    <p>
        CodeSniffer can be extended to support custom code standards, but this requires a non-trivial amount of work.
        You can define custom rules ("sniffs") and build a custom coding standard based on those rules.
        Learn more about this <a href="https://github.com/squizlabs/PHP_CodeSniffer/wiki/Coding-Standard-Tutorial">here</a>.
    </p>
</article>
