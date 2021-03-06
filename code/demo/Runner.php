<?php
namespace Demo;

class Runner
{
    private $request;
    private $resultsId;

    private $tmpDir;

    private $sourceFiles = [];

    public function run(array $request, int $resultsId): void
    {
        $this->request = $request;
        $this->resultsId = $resultsId;

        $this->verifyCaptcha();

        $this->initDir();
        $this->initFiles();
        if ($this->sourceFiles) {
            $this->runPhpcs();
            $this->runPhpmd();
            $this->runPhpMetrics();
        }
        $this->generateResultsPage();
        $this->removeSourceFiles();
        $this->installResults();
    }

    protected function verifyCaptcha(): void
    {
        $secret = getenv('PHPSTATICANALYSIS_CAPTCHA_SECRET');
        if (!$secret) {
            return; // Validation disabled
        }

        $postdata = http_build_query([
            'secret' => $secret,
            'response' => $this->request['g-recaptcha-response'] ?? '',
            'remoteip' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'],
        ]);

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]);

        $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $json = @json_decode($result);
        if (empty($json->success)) {
            http_response_code(401);
            die('Captcha not verified');
        }
    }

    protected function initDir(): void
    {
        $this->tmpDir = __DIR__ . '/workingdir/' . $this->resultsId;
        mkdir($this->tmpDir);
        mkdir($this->tmpDir . '/code');
        mkdir($this->tmpDir . '/phpmetrics');
    }

    protected function initFiles(): void
    {
        $this->initFile($_REQUEST['file1'] ?? null);
        $this->initFile($_REQUEST['file2'] ?? null);
        $this->initFile($_REQUEST['file3'] ?? null);
    }

    protected function initFile(?string $file): void
    {
        // Empty file
        if (!trim(str_replace('<?php', '', (string) $file))) {
            return;
        }
        // Form submission sends Windows-style newlines
        $file = str_replace("\r\n", "\n", $file);

        $filename = 'file' . (count($this->sourceFiles) + 1) . '.php';
        $this->sourceFiles[] = $filename;
        file_put_contents($this->tmpDir . '/code/' . $filename, $file);
    }

    protected function runPhpcs(): void
    {
        $command = '/code/demo/vendor/bin/phpcs --standard=PSR2 ' . $this->tmpDir . '/code';
        exec($command, $output, $return);
        file_put_contents(
            $this->tmpDir . '/phpcs.txt',
            '$ ' . $command . "\n\n" . implode("\n", $output) . "\n\nExit code: " . $return
        );
    }

    protected function runPhpmd(): void
    {
        $command = '/code/demo/vendor/bin/phpmd ' . $this->tmpDir . '/code text cleancode,codesize,design,naming,unusedcode,controversial';
        exec($command, $output, $return);
        file_put_contents(
            $this->tmpDir . '/phpmd.txt',
            '$ ' . $command . "\n\n" . implode("\n", $output) . "\n\nExit code: " . $return
        );

        $output = [];
        $command = '/code/demo/vendor/bin/phpmd ' . $this->tmpDir . '/code xml cleancode,codesize,design,naming,unusedcode,controversial';
        exec($command, $output, $return);
        file_put_contents(
            $this->tmpDir . '/phpmd.xml',
            implode("\n", $output)
        );
    }

    protected function runPhpMetrics(): void
    {
        $command = '/code/demo/vendor/bin/phpmetrics --report-html='
            . $this->tmpDir . '/phpmetrics --report-json='
            . $this->tmpDir . '/phpmetrics/report.json --report-violations='
            . $this->tmpDir . '/phpmetrics/report-violations.xml '
            . $this->tmpDir . '/code';
        exec($command, $output, $return);
        file_put_contents(
            $this->tmpDir . '/phpmetrics/stdout.txt',
            '$ ' . str_replace(' ', " \\\n    ", $command) . "\n\n" . implode("\n", $output) . "\n\nExit code: " . $return
        );
    }

    protected function generateResultsPage(): void
    {
        $results = '';
        if ($this->sourceFiles) {
            $results .= '<article>';
            $results .= '<h1>Demo results</h1>';
            $results .= '<p>Your code has been processed by several static analysis tools, and the results are below. ';
            $results .= 'These results are only accessible to others if you share the link with them. ';
            $results .= 'These results will expire in a few days.</p>';
            $results .= '</article>';

            $results .= '<article>';
            $results .= '<h2>PHP_CodeSniffer</h2>';
            $results .= '<p><a href="/intro-to-phpcs">PHP_CodeSniffer</a> checks code style (whitespace, capitalization, etc.)</p>';
            $results .= '<p>Here are the results from PHP_CodeSniffer when used to check for the popular <a href="https://www.php-fig.org/psr/psr-2/">PSR-2</a> coding standard.</p>';
            $results .= '<pre>';
            $results .= htmlspecialchars(file_get_contents($this->tmpDir . '/phpcs.txt'));
            $results .= '</pre>';
            $results .= '</article>';

            $results .= '<article>';
            $results .= '<h2>PHP Mess Detector</h2>';
            $results .= '<p><a href="/intro-to-phpmd">PHP Mess Detector</a> checks for "messes" such as complex functions or calls to static functions (which can be hard to unit test).</p>';
            $results .= '<p>Here are the results from PHP Mess Detector when used to check all rules with default settings.</p>';
            $results .= '<pre>';
            $results .= htmlspecialchars(file_get_contents($this->tmpDir . '/phpmd.txt'));
            $results .= '</pre>';
            $results .= '<p>PHP Mess Detector can also output in XML format. See the XML output <a href="' . $this->resultsId . '/phpmd.xml">here</a>.</p>';
            $results .= '</article>';

            $results .= '<article>';
            $results .= '<h2>PhpMetrics</h2>';
            $results .= '<p><a href="/intro-to-php-metrics">PhpMetrics</a> generates an HTML report about your code.</p>';
            $results .= '<p>Here are the results from PhpMetrics &mdash; both the HTML report and the console output.</p>';
            $results .= '<a href="/demo-results/' . $this->resultsId . '/phpmetrics/index.html" class="megalink">PhpMetrics HTML Report</a>';
            $results .= '<pre>';
            $results .= htmlspecialchars(file_get_contents($this->tmpDir . '/phpmetrics/stdout.txt'));
            $results .= '</pre>';
            $results .= '<p>PhpMetrics can also output in JSON, CSV, XML formats. Click to see the ';
            $results .= '<a href="' . $this->resultsId . '/phpmetrics/report.json">JSON output</a> or ';
            $results .= '<a href="' . $this->resultsId . '/phpmetrics/report-violations.xml">XML output</a> (code quality violations only).';
            $results .= '</p>';
            $results .= '</article>';
        } else {
            $results .= '<article><p>Please provide some PHP code to run this demo.';
            $results .= ' <a href="/demo">Try again.</a></p></article>';
        }

        file_put_contents($this->tmpDir . '/results.html', $results);
    }

    protected function removeSourceFiles(): void
    {
        // It is important for security to delete the uploaded PHP before the results are moved to the HTML folder
        foreach ($this->sourceFiles as $file) {
            unlink($this->tmpDir . '/code/' . $file);
        }
        rmdir($this->tmpDir . '/code');
    }

    protected function installResults(): void
    {
        exec(sprintf('mv "%s" "%s"', $this->tmpDir, '/var/www/html/demo-results'));
    }
}
