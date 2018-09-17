<article>

	<h1>Tools demo</h1>

	<p>
		You can try these static analysis tools right now, no download required.<br>
		The demo supports these tools:
		<ul>
			<li><a href="/intro-to-phpcs">PHP_CodeSniffer</a></li>
			<li>More coming soon...</li>
		</ul>
	</p>

	<form action="/demo-results/process.php" method="POST">
		<fieldset>
			<legend>Paste up to 3 source files to try the demo</legend>

			<textarea name="file1" rows="20" cols="40">&lt;?php

</textarea>
			<textarea name="file2" rows="20" cols="40">&lt;?php

</textarea>
			<textarea name="file3" rows="20" cols="40">&lt;?php

</textarea>

			<br>
			<input type="submit" value="See results">

		</fieldset>
	</form>

</article>
