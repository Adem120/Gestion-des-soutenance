<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
@if($errors->any())
    @foreach($errors->all() as $error)
        <p>{{$error}}</p>
    @endforeach
@endif
<?php
    $token = $_GET['token'];
?>
<body>
    <form  action={{url('resetpassword')}} method="post">
@csrf
        <input type="hidden" name="token" value={{$token}}>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation">
        <button type="submit" class="btn btn-success">Reset Password</button>
    
</body>
</html>