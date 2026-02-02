<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Redirecting...</title>
</head>
<body>

<form id="checkoutRedirect" method="POST" action="{{ route('checkout') }}">
    @csrf
    <input type="hidden" name="cart_items[]" value="{{ $cartId }}">
</form>

<script>
    document.getElementById('checkoutRedirect').submit();
</script>

</body>
</html>
