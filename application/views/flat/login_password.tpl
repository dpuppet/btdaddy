<?php include('header.tpl')?>
<div class="container-fluid-full" xmlns="http://www.w3.org/1999/html">
    <div class="row-fluid">

        <?php include('menu.tpl');?>

        <!-- start: Content -->
        <div id="content" class="span10">


            <div class="row-fluid">
                <div class="box span6">
                    <div class="box-header">
                        <h2><i class="icon-lock"></i>登陆密码设置</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" />
                        <fieldset>

                            <div class="control-group">
                                <label class="control-label" for="appendedInput">原始密码</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input id="old_cash_password" class="input-large" type="password" />
                                    </div>
                                </div>
                            </div>


                            <div class="control-group">
                                <label class="control-label">新密码</label>
                                <div class="controls">
                                    <input id="new_password" class="input-large" type="password"/>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">再次确认</label>
                                <div class="controls">
                                    <input id="reconfirm" class="input-large"  type="password"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">验证码</label>
                                <div class="controls">
                                    <input autocomplete="off" id="code" type="text" />
                                    <div><a href="javascript:refresh_code()"><img id='validate_code' src="/validate_code"/></a></div>
                                </div>
                                <div class="control-group">

                                    <div id="error-msg" class=" alert alert-danger hide"></div>
                                </div>

                        </fieldset>
                        </form>
                        <div class="form-actions">
                            <button type="submit" id="change_btn" class="btn btn-success btn-block btn-large">修改密码</button>
                        </div>
                    </div>
                </div><!--/span-->

            </div><!--/row-->

        </div>
        <!-- end: Content -->

    </div><!--/fluid-row-->



    <?php include('footer.tpl')?>

    <script src="/static/js/login_password.js"></script>
