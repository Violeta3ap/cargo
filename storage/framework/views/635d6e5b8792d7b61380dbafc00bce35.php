<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reģistrācija</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


 <style>
        body {
            background-color: #ffffff;
            font-family: Arial, sans-serif;
        }

        .center-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-bloom {
            background-color: #59c1cf;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
        }

        .btn-bloom:hover {
            background-color: #59c1cf;
        }
        </style>

</head>

<body>


    <div class="center-wrap">
        <div class="card custom p-4">
            <h2 class="text-center mb-4">Reģistrācija</h2>




            <!-- Ziņojumi par kļūdām -->
            <?php if($errors->any()): ?>
                <div style="background-color: #ffe8e8; border: 1px solid #ff9999; color: #c00; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Login forma -->
            <form method="POST" action="/Login/submit">
                <?php echo csrf_field(); ?>

                <div style="margin-bottom: 20px;">
                    <label for="name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Lietotājvārds</label>
                    <input type="text" id="name" name="name" required value="<?php echo e(old('name')); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Parole</label>
                    <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                </div>

                <button type="submit" style="width: 100%; padding: 12px; background: linear-gradient(to right, #59c1cf, #4a9aaa); color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: transform 0.2s;">Pieteikties</button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="/" style="color: #59c1cf; text-decoration: none; font-size: 14px;">Atpakaļ uz sākumlapu</a>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH C:\Users\tinyd\Herd\ccargo\resources\views/Login.blade.php ENDPATH**/ ?>