<?php echo Debug::vars($meta, $data) ?>

<?php if ($links): ?>
<h5>Additional Result Pages</h5>
<p><?php echo implode(' / ', $links) ?></p>
<?php endif ?>
