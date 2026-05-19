<?php if (isset($errors) && !empty($errors)) : ?>

    <?php foreach ($errors as $error) : ?>

        <div class="message bg-red-100 p-3 my-3 rounded">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endforeach; ?>

<?php endif; ?>