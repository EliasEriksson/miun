<?php
/**
 * makes sure the variables are always defined even if included from a file where they are not set
 */
if (!isset($title)) {
    $title = "";
}
if (!isset($preamble)) {
    $preamble = "";
}
if (!isset($content)) {
    $content = "";
}
if (!isset($errorMessage)) {
    $errorMessage = "";
}
?>

<form class="article-form" method="post" enctype="application/x-www-form-urlencoded">
    <span class="error-message"><?=$errorMessage?></span>
    <label>
        <span class="label-text">Titel</span>
        <input name="title" class="input" type="text" value="<?=$title?>">
    </label>
    <label>
        <span class="label-text">Ingress</span>
        <textarea name="preamble" class="input textarea"><?=$preamble?></textarea>
    </label>
    <label>
        <span class="label-text">Artikel</span>
        <textarea name="article" class="input textarea"><?=$content?></textarea>
    </label>
    <div class="submit-wrapper">
        <input  class="button" name="submit" type="submit" value="Publicera">
    </div>
</form>