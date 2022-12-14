<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

<?php if ($showLabel && $options['label'] !== false): ?>
    <?= Form::label($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>

<?php if ($showField): ?>
    <input type='hidden' name="<?php echo substr($name, 0, strpos($name, '[]')); ?>" value="novalue">
    <?php $emptyVal = $options['empty_value'] ? ['' => $options['empty_value']] : null; ?>
    <?= Form::select($name, (array)$options['choices'], $options['selected'], $options['attr']) ?>
    <?php include 'help_block.php' ?>
<?php endif; ?>

<?php include 'errors.php' ?>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </div>
    <?php endif; ?>
<?php endif; ?>
