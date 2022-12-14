<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

<?php if (array_get($options, 'is_child', false)): ?>
    <div class="checkbox">
        <label>
<?php endif; ?>
<?php if ($showField): ?>
    <?= Form::checkbox($name, $options['value'], $options['checked'], $options['attr']) ?>

    <?php include 'help_block.php' ?>
<?php endif; ?>

<?php if ($showLabel && $options['label'] !== false && !array_get($options, 'is_child', false)): ?>
    <?php if ($options['is_child']): ?>
        <label <?= $options['labelAttrs'] ?>><?= $options['label'] ?></label>
    <?php else: ?>
        <?= Form::label($name, $options['label'], $options['label_attr']) ?>
    <?php endif; ?>
<?php endif; ?>

<?php if (array_get($options, 'is_child', false)): ?>
            <?= $options['label'] ?>
        </label>
    </div>
<?php endif; ?>

<?php include 'errors.php' ?>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </div>
    <?php endif; ?>
<?php endif; ?>
