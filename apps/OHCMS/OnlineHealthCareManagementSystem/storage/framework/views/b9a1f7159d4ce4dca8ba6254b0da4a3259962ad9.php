<?php $attributes = $attributes->exceptProps(['option'=>[],'value'=>""]); ?>
<?php foreach (array_filter((['option'=>[],'value'=>""]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<select <?php echo $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']); ?>>
    <?php $__currentLoopData = $option; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($value == $item): ?>
        <option value="<?php echo e($item); ?>" selected><?php echo e($item); ?></option>
    <?php else: ?>
    <option value="<?php echo e($item); ?>"><?php echo e($item); ?></option>
    <?php endif; ?>
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select><?php /**PATH /var/www/html/resources/views/components/select.blade.php ENDPATH**/ ?>