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

        $this->initDir();
        $this->initFiles();
        if ($this->sourceFiles) {
            $this->runPhpcs();
        }
        $this->generateResultsPage();
        $this->removeSourceFiles();
        $this->installResults();
    }

    protected function initDir(): void
    {
        $this->tmpDir = __DIR__ . '/workingdir/' . $this->resultsId;
        mkdir($this->tmpDir);
        mkdir($this->tmpDir . '/code');
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
        if (!trim(str_replace('<?php', '', $file))) {
            return;
        }

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
            $command . "\n\n" . implode("\n", $output) . "\n\nExit code: " . $return
        );
    }

    protected function generateResultsPage(): void
    {
        if ($this->sourceFiles) {
            $results = '<article>';
            $results .= '<h2>PHP_CodeSniffer</h2>';
            $results .= '<p>Here are the results from <a href="/intro-to-phpcs">PHP_CodeSniffer</a> when used to check for the PSR-2 coding standard.</p>';
            $results .= '<pre>';
            $results .= htmlspecialchars(file_get_contents($this->tmpDir . '/phpcs.txt'));
            $results .= '</pre>';
            $results .= '</article>';
        } else {
            $results = '<article><p>Please provide some PHP code to run this demo.';
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
