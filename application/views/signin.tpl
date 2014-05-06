
<!DOCTYPE html>
<html>
<head>
    <title>BtDaddy - 比特币交易中心</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-Frame-Options" content="deny" />

    <link data-turbolinks-track="true" href="/static/css/0ec26bbb350d6eac.css" media="all" rel="stylesheet" type="text/css" />
    <script src="/static/js/jquery.2.0.3.js"></script>
    <script src="/static/js/3d21b4f1ffe415.js" type="text/javascript"></script>

</head>
</head>
<body class="app signed-out  sessions new">
  <div class="page-container">
     <div class="navbar navbar-static-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a href="/" class="brand">BtDaddy</a>
                <div class="nav-collapse pull-right">
                    <ul class="nav">

                    </ul>

                    <ul class="nav">

                        <li class=""><a href="/login" style="margin-top:5px;"">登陆</a></li>
                        <li class=""><a href="/signup" class="btn">注册</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="page">
        <div class="flash">
</div>



<div class='row'>
  <div class='span4 offset4'>
    
    <div class="account-form thumbnail">
      <h1>登陆</h1>
      <form accept-charset="UTF-8" action="" class="form-vertical" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="IPkpHzAMWAA8u4RToAlJM7m15FI5tv9MAQrs1OHYCZo=" /></div>

      <div class="control-group">
        <label class="control-label" for="email">Email</label>
        <div class="controls">
          <input class="focus" id="email" name="email" placeholder="Email" tabindex="1" type="email" />
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="password">密码</label>
        <div class="controls">
          <input autocomplete="off" id="password" name="password" placeholder="密码" tabindex="2" type="password" />
        </div>
      </div>

          <div class="control-group"
          <label class="control-label" for="code">验证码</label>
          <div class="controls">
              <input autocomplete="off" id="code" placeholder="验证码" tabindex="2" type="text" />
              <a href="javascript:refresh_code()"><img id='validate_code' src="/validate_code"/></a>
          </div>
    </div>
      <div class="alert alert-danger hide" id="msg"></div>
      <div class="control-group">
        <div class="controls">
          <input class="btn btn-primary pull-right" id="signin_button" name="commit" tabindex="4" type="submit" value="登陆" />
		  <a href="/signup"><input class="btn btn-success pull-left" id="signup_button" name="commit" type="submit" tabindex="5" value="注册" /></a>
        </div>
      </div>

</form>    </div>

    
  </div>
</div>
<br>
    </div>
  </div>
</body>

  
<script src="/static/js/login.js"></script>
</html>
