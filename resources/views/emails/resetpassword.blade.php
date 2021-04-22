<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
<div class="row">
<div class="col-sm-12">
<h1>Change Password</h1>
</div>
</div>
<div class="row">
<div class="col-sm-6 col-sm-offset-3">
<p class="text-center">Use the form below to change your password. Your password cannot be the same as your username.</p>
        
        <form action="{{url('password-change')}}" class="form-horizontal" enctype="multipart/form-data" method="post">
         <h1>{{$id}}</h1>
        <input type="password" class="input-lg form-control" name="password" placeholder="New Password" autocomplete="off">

        <input type="password" class="input-lg form-control" name="password_confirm"  placeholder="Repeat Password" autocomplete="off">
        <input type="hidden"  name="custId" value="{{$id}}">
 
        <input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Changing Password..." value="Change Password">
        </form>
</div><!--/col-sm-6-->
</div><!--/row-->
</div>
