<?php

require "vendor/autoload.php";

session_start();

use App\QuestionManager;

$manager = new QuestionManager;
$manager->initialize();

$score = $manager->computeScore($_SESSION['answers']);
$questionSize = $manager->getQuestionSize();

// Generate the file contents: the user registration information, the user’s answers, and score
$content = "Complete Name: {$_SESSION['user_completename']}\nEmail: {$_SESSION['user_email']}\nBirthdate: {$_SESSION['user_birthdate']}\nScore: {$score} out of {$questionSize}\nAnswers:\n";
foreach ($_SESSION['answers'] as $index => $answer) {
    $question = $manager->retrieveQuestion($index);
    if ($question !== null) {
        $isCorrect = ($answer === $question->getAnswer()) ? 'correct' : 'incorrect';
        $content .= "$index. $answer ($isCorrect)\n";
    }
}

// Write the file contents to results.txt
file_put_contents('results.txt', $content);

// Tell the user's browser to download the response as a file named results.txt
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=results.txt');

readfile('results.txt');
exit;
?>