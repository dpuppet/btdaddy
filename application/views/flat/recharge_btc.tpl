<?php include('header.tpl')?>
<div class="container-fluid-full" xmlns="http://www.w3.org/1999/html">
		<div class="row-fluid">
				
<?php include('menu.tpl');?>
						
			<!-- start: Content -->
			<div id="content" class="span10">
			

			<div class="row-fluid">
				<div class="box span7">
					<div class="box-header">
						<h2><i class="icon-money"></i>比特币充值</h2>
					</div>
					<div class="box-content">

                        <div class="well">
                            <div class="alert alert-success"><i class="icon-lock"></i>您的比特币在被系统收到后，马上转入离线钱包，100%安全</div>
                            <h3>充值地址：</h3>
                            <h2><?php echo $btc_address; ?></h2>
                        </div>

                        <div class="alert alert-warning"><i class="icon-info-sign"></i> 请不要多次向同一个地址充值，在成功一笔交易后，系统会为你自动生成新的地址</div>
                    </div>
				</div><!--/span-->

                <div class="box span5">
                    <div class="box-header">
                        <h2><i class="icon-align-justify"></i><span class="break"></span>充值记录</h2>
                        <div class="box-icon">
                            <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>充值流水号</th>
                                <th>充值时间</th>
                                <th>充值数额</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php echo $recharge_list ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--/span-->
			</div><!--/row-->
            <div class="row-fluid">

            </div>
					
			</div>
			<!-- end: Content -->
				
				</div><!--/fluid-row-->



<?php include('footer.tpl')?>

