 <?php if (isset($component)) { $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\GuestLayout::class, []); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
    <div class="mt-20 ml-12 grid grid-cols-4">
        <div class="flex-col col-span-2">
            <h1 class="text-4xl md:text-8xl font-bold text-blue-500">Online Medical Consultation system</h1>
            <h1 class="text-xl mt-20 text-blue-500 border-l-4 border-blue-700 pl-6">In this platform, We offer affordable solution for Health Industry</h1>
            <div class="mt-20">
                <span class="uppercase shadow-lg rounded-lg font-bold p-4 text-2xl text-gray-200 bg-blue-500 hover:bg-gray-700 transition ease-in-out duration-150">
                    <a href="#">
                        learn more
                    </a>
                </span>
            </div>
        </div>
        <div class="col-span-2">
        </div>
    </div>
 <?php if (isset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015)): ?>
<?php $component = $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015; ?>
<?php unset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?> 
            

<?php /**PATH /var/www/html/resources/views/index.blade.php ENDPATH**/ ?>