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
		$this->runPhpcs();
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
		file_put_contents($this->tmpDir . '/phpcs.txt', 'this is the phpcs results');
	}

	protected function generateResultsPage(): void
	{
		$results = '<p>Here are the results from PHPCS</p>';
		$results .= file_get_contents($this->tmpDir . '/phpcs.txt');

		file_put_contents($this->tmpDir . '/results.html', $results);
	}

	protected function removeSourceFiles(): void
	{
		// It is important for security to delete the uploaded PHP before the results are moved to the HTML folder
		foreach ($this->sourceFiles as $file) {
			unlink($this->tmpDir . '/code/' . $file);
		}
	}

	protected function installResults(): void
	{
		exec(sprintf('mv "%s" "%s"', $this->tmpDir, '/var/www/html/demo/results'));
	}
}
