<article>
    <h2>Preventing complex code -- cyclomatic complexity</h2>

    <div>
        Last updated on <time>Sep. 15th, 2018</time>.
    </div>

    <p>
        Cyclomatic complexity is one measure of code complexity.
        Here is an example of a method with a cyclomatic complexity of 5:
    </p>

    <pre>
public function x($numberA, $numberB, $numberC): void           // each method starts with a complexity of 1
{
    if ($numberA < $numberB) {                                  // +1 complexity for this "if" statement
        echo 'hello';
    }
    for ($i = 0; $i < $numberC; $i++) {                         // +1 complexity for this "for" statement
        if ($numberA + $numberB > $i || $numberA == $numberB) { // +2 complexity for this compound "if" statement
            echo ' world';
        }
    }
}
    </pre>

    <p>
        When methods become complex, they can be hard to test. Let's look at another example.
    </p>

    <pre>
public function evaluateSentence(string $sentence): string
{
    $info = '';
    if (stripos($sentence, 'dog') !== false) {
        $info .= 'Sentence has a dog. ';
    }
    if (stripos($sentence, 'cat') !== false) {
        $info .= 'sentence has a cat. ';
    }
    if (stripos($sentence, 'fox') !== false) {
        $info .= 'sentence has a fox. ';
    }
    if (stripos($sentence, 'llama') !== false) {
        $info .= 'sentence has a llama. ';
    }
    return $info;
}
    </pre>

    <p>
        Let's think about how many different tests we need for this method.
        To test every statement of this method, we only need a single test: "I like dogs and cats and foxes and llamas."
        But if we want to test every possible return value, we need many tests:
        <ul>
            <li>Sentence with only dog</li>
            <li>Sentence with only cat</li>
            <li>Sentence with only fox</li>
            <li>Sentence with only llama</li>
            <li>Sentence with only dog and cat</li>
            <li>Sentence with only dog and fox</li>
            <li>Sentence with only dog and llama</li>
            <li>...and more (16 total cases)</li>
        </ul>
    </p>

    <p>
        Due to this explosion of potential scenarios, it is recommended to avoid complex methods.
        Refactoring these complex methods leads to more testable code, which is more maintainable code.
    </p>

    <h3>Checking complexity</h3>

    <p>
        Static analysis tools can examine code and produce a list of complex methods.
        One such tool is <a href="/intro-to-phpmd">phpmd</a>.
    </p>

    <p>
        It is beneficial to give feedback to developers quickly when a method becomes too complex.
        The feedback helps the developer think "how can I test this method?" or "how can I make this logic more clear?"
        There are multiple ways to check complexity, including
        <i>IDE integration</i> while the code is written,
        <i>git hooks</i> that can run each time code is committed, and
        <i>CI systems</i> that run on each commit or each pull request.
    </p>
</article>
