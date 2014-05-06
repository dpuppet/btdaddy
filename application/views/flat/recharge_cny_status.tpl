<?php include('header.tpl')?>
<div class="container-fluid-full" xmlns="http://www.w3.org/1999/html">
		<div class="row-fluid">
				
<?php include('menu.tpl');?>
						
			<!-- start: Content -->
			<div id="content" class="span10">
			

			<div class="row-fluid">

                <?php
                    if ($status == 'success'){
                        echo ('<div class="alert alert-success"><strong>充值成功！</strong>充值金额：'.$amount.'元</div>');
                    }else{
                        echo ('<div class="alert alert-error"><strong>充值失败</strong></div>');
                    }
                ?>

			</div><!--/row-->

					
			</div>
			<!-- end: Content -->
				
				</div><!--/fluid-row-->



<?php include('footer.tpl')?>
    <script src="/static/js/recharge_cny.js"></script>

    <div class="modal fade" id="confirm_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">充值确认</h4>
                </div>
                <div class="modal-body">
                    <p id="confirm_text"></p>
                </div>
                <div class="modal-footer">

                    <form action="https://pay.ecpss.cn/sslpayment" method="post" id="pay_form" name="E_FORM">

                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->