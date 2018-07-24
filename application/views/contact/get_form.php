<?php echo form_open('', 'id="dataListing" name="dataListing"');?>
	<input type="hidden" value="<?=$this->controller_url?>delete/multi" id="multi_delete" />
	<div class="portlet light portlet-fit ">
		<div class="portlet-title">
			<div class="caption">
				<i class=" icon-layers font-green"></i>
				<span class="caption-subject font-green bold uppercase"><?=@$section_title?></span>
			</div>
			<div class="actions">
				<a class="btn btn-circle btn-icon btn-default" title="Back" href="javascript:;" onclick="window.history.go(-1); return false;"><i class="icon-action-undo"></i> Back</a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="row">
				<div class="col-sm-12">
					<div class="note note-success">
						<h4 class="block">Just one step far away ftom generating your own subscribers.</h4>
						<p>Copy below form and put into your site. Make sure that you have added Return URL Otherwise subscribers will not added into list.</p>
					</div>

					<textarea rows="13" disabled="true" style="width: 100%;">
						<div id="app-list-form">
							<form action="<?=base_url()?>api/add_sub/<?=$LIST_ID;?>">
								<input type="hidden" name="CLIENT_ID" value="<?=$CLIENT_ID;?>" />
								<input type="hidden" name="CLIENT_SECRET" value="<?=$CLIENT_SECRET;?>" />
								<input type="hidden" name="return_url" value="" />
								<p>First Name : <input name="first_name" id="first_name" value="" required="required"></p>
								<p>Last Name : <input name="last_name" id="last_name" value="" required="required"></p>
								<p>Phone : <input name="phone" id="phone" value="" required="required"></p>
								<p>Email : <input name="email" id="email" value="" required="required"></p>
								<p><input type="submit" name="submit" id="submit" value="Submit"></p>
							</form>
						</div>
				 	</textarea>
				</div>
			</div>
		</div>
	</div>
</form>