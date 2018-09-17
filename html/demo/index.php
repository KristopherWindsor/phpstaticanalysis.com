<?php

require '/code/template/open.html';
?>


<!-- 
1) at the index, paste in up to 3 source files... then submit
2) go "process.php" or whatever that makes the report, then redirects to it like /demo/results/behberh
   (permalink until container is restarted; could upload to s3 instead)
-->


<h1>Tools demo</h1>

<p>
	You can try these static analysis tools right now, no download required.<br>
	The demo supports these tools:
	<ul>
		<li><a href="/intro-to-phpcs">PHP_CodeSniffer</a></li>
		<li>More coming soon...</li>
	</ul>
</p>

<form action="process.php" method="POST">
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

<?php

require '/code/template/close.html';
