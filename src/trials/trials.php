<?php 
function checkWord($input, $letter){
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($input) && strtolower($input[0]) !== $letter) {
        return "* This word must start with the letter {$letter}!";
    } else {
        return "";
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Run trials
        </title>
    </head>
    <body>
		<h2>Intro greet</h2>
        <form method="post" action="">
			Your name:
			<br>
  		    <input type="text" name="name">
 			<br><br>
  		    What is the best thing about learning to code:
  		    <br>
  		    <input type="text" name="best">
  		    <br><br> 
            <p>Enter a word that starts with letter b</p>
			<br>
			<input type="text" name="b-word" id="b-word" value="<?= isset($_POST['b-word']) ? $_POST['b-word'] : ''; ?>">
    		<br>
   			<p class="error" id="b-error"><?= checkWord($_POST['b-word'] ?? '', 'b'); ?></p>
			<input type="submit" value="Submit Words">
		</form>
		<a href="index.php">Reset</a>
		<div id="form-output">
			<?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
				<p id="name"> Hello, <?= $_POST["name"]?>!</p>
			    <p id="name">Hello, <?= htmlspecialchars($_POST["name"]) ?>!</p>
    	    	<p id="best">I am glad you enjoy <?= $_POST["best"] ?>.</p>
			<?php endif; ?>
            <?php 
            $bWordError = checkWord($_POST['b-word'] ?? '', 'b');
            if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($bWordError)): ?>
                <h3>"b" is for: <?= htmlspecialchars($_POST['b-word']) ?></h3>
            <?php endif; ?>
        </div>
    </body>
</html>
<!-- <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <h3>"b" is for: <?= isset($_POST['b-word']) ? htmlspecialchars($_POST['b-word']) : ''; ?></h3>
            <?php endif; ?> -->