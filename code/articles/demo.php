<style>
    fieldset {display: inline-block;}
</style>

<article>

    <h1>Tools demo</h1>

    <p>
        You can try several PHP static analysis tools right now, no download or login required.
        Your source code will <em>not</em> be saved on our server.
        Paste up to 3 source files below in order to evaluate them with static analysis tools.
        The demo supports these tools:
        <ul>
            <li><a href="/intro-to-phpcs">PHP_CodeSniffer</a></li>
            <li><a href="/intro-to-phpmd">PHP Mess Detector</a></li>
            <li><a href="/intro-to-php-metrics">PhpMetrics</a></li>
        </ul>
    </p>

    <form action="/demo-results/process.php" method="POST" onsubmit="ga('send', 'event', 'demo', 'requested')">
        <fieldset>
            <legend>Try the demo</legend>

            <textarea name="file1" rows="20" cols="40">&lt;?php
namespace Vehicle;

abstract class Vehicle {
    public $wheels = [];
    public $thisVariableIsVeryVeryVeryLong;

    public function getMaxSpeed(): int
    {
        return 200;
    }

    public function getValue(): int
    {
        $VAL = 0;
        foreach ($this->wheels as $wheel)
            $VAL += $wheel->getValue();
        return $VAL;
    }
}
</textarea>
            <textarea name="file2" rows="20" cols="40">&lt;?php
namespace Vehicle;

class Truck extends Vehicle
{
    private $isTowing;

    /**
     * Gets the maximum truck speed
     */
    public function getMaxSpeed(): int
    {
        return $this->isTowing ? 40 : 120;
    }

    public function methodIsTooComplex(): void
    {
        if (true) {
            if (true) {
                if (true) {
                    if (true) {
                        if (true) {
                            if (true) {
                                if (true) {
                                    if (true) {
                                        if (true) {
                                            if (true) {
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
</textarea>
            <textarea name="file3" rows="20" cols="40">&lt;?php

</textarea>

            <br>
            <input type="submit" value="See results">
        </fieldset>
        <div class="g-recaptcha" data-sitekey="6LensnIUAAAAABDofnIUxSd8ZMlLfmlBvdlAZIEz"></div>
    </form>

</article>
