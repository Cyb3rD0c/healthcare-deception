<?php $attributes = $attributes->exceptProps(['value'=>"G4S Dhaka House, 22 Progoti Sarani, Dhaka 1212, Bangladesh"]); ?>
<?php foreach (array_filter((['value'=>"G4S Dhaka House, 22 Progoti Sarani, Dhaka 1212, Bangladesh"]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<textarea <?php echo $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']); ?>>
<?php echo e($value); ?>

</textarea><?php /**PATH /var/www/html/resources/views/components/textarea.blade.php ENDPATH**/ ?>