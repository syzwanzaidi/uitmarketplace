<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Redirecting...</title>
</head>
<body>

<form id="checkoutRedirect" method="POST" action="<?php echo e(route('checkout')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="cart_items[]" value="<?php echo e($cartId); ?>">
</form>

<script>
    document.getElementById('checkoutRedirect').submit();
</script>

</body>
</html>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/redirects/post-checkout.blade.php ENDPATH**/ ?>