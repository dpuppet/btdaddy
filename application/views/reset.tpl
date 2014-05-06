
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
                                <li class=""><a href="/home">交易中心</a></li>
                                <li class=""><a href="/about">交易行情</a></li>
                                <li class="dropdown dropdown-hover">

                                </li>
                            </ul>

                            <ul class="nav">
                                <li class='top-balance dropdown dropdown-hover'>
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">比特币当前价格: $1,126.81<b class='caret'></b></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li class=""><a href="/buy-bitcoin">Buy Bitcoin</a></li>
                                        <li class=""><a href="/security">Security</a></li>
                                        <li class=""><a href="/charts">Charts</a></li>
                                        <li class=""><a href="/docs/api/overview">API</a></li>
                                        <li> <a href="/help">Support</a></li>
                                        <li><a href="http://blog.coinbase.com">Blog</a></li>
                                    </ul>
                                </li>
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
                              <h1>取回密码</h1>
                              <form accept-charset="UTF-8" action="" class="form-vertical" method="post">

                                <div class="control-group">
                                    <label class="control-label" for="email">Email</label>
                                    <div class="controls">
                                      <input class="focus" id="email" name="email" placeholder="Email" tabindex="1" type="email" />
                                    </div>
                                </div>

                                <div class="control-group"
                                  <label class="control-label" for="code">验证码</label>
                                  <div class="controls">
                                      <input autocomplete="off" id="code" placeholder="验证码" tabindex="2" type="text" />
                                      <a href="javascript:refresh_code()"><img id='validate_code' src="validate_code"/></a>
                                  </div>
                                </div>

                                <div class="alert alert-danger hide" id="msg"></div>
                                <div class="alert alert-success hide" id="success-msg"></div>
                                <div class="control-group">
                                    <div class="controls">
                                      <input class="btn btn-primary pull-right" id="reset_button" name="commit" tabindex="4" type="submit" value="发送到邮箱" />
                                    </div>
                                </div>
                              </form>
                        </div>
                  </div>
            </div>
            <br>
        </div>
  </div>
</body>

  
<script src="/static/js/reset.js"></script>
</html>
