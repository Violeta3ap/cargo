

<?php $__env->startSection('content'); ?>
<div style="display: flex">
<h2>Vagona raksturojuma dati</h2> 
<nav class="navigacija" style="   background-color: #ffffff;">
    <a href="/">Atpakaļ uz mājas lapu</a>
<a href="/VagonaRaksturojums/jauns" >Jauns ieraksts</a>
</nav>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>VeidaID</th>
            <th>KravasID</th>
            <th>Celtspeja</th>
            <th>VagonaNumurs</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $dati; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($item->VagonaID); ?></td>
            <td><?php echo e($item->VeidaID); ?></td>
            <td><?php echo e($item->KravasID); ?></td>
            <td><?php echo e($item->Celtspeja); ?></td>
            <td><?php echo e($item->VagonaNumurs); ?></td>
            <td>



                <a href="/VagonaRaksturojums/<?php echo e($item->VagonaID); ?>/details" style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;">Detalizēta</a>
                <a href="/VagonaRaksturojums/<?php echo e($item->VagonaID); ?>/edit"style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-warning">Rediģēt</a>
                <a href="/VagonaRaksturojums/<?php echo e($item->VagonaID); ?>/delete"style="border-radius:8px;  border: 1px solid #59c1cf; 
                padding: 5px; color: #000000; text-decoration: none; background-color: #59c1cf;" class="btn btn-sm btn-danger">Dzēst</a>

            
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<style>
    .table {
        border-collapse: collapse;
    }
    
    .table thead {
        background-color: #59c1cf;
        color: white;
    }
    
    .table thead th {
        border: 1px solid #59c1cf;
        padding: 12px;
        font-weight: bold;
    }
    
    .table tbody tr:hover {
        background-color: #e8f5f7;
    }
    
    .table tbody td {
        border: 1px solid #ddd;
        padding: 10px;
    }
</style>

<?php $__env->stopSection(); ?>


<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>  
<?php endif; ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\tinyd\Herd\ccargo\resources\views/VagonaRaksturojums.blade.php ENDPATH**/ ?>