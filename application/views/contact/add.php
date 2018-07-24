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
		
		<?php echo form_open('', 'id="common_form"');?>
			<input type="hidden" name="list_type" value="1">
			<div class="form-body">

				<div class="row mt-repeater-row">
					<div class="col-md-1"></div>
					<?php /*<div class="col-md-5">
						<?php
							$field_name  = "list_type";
							$field_title = "List Type";
						?>
						<div class="form-group form-md-line-input <?=(!empty(form_error($field_name))) ? "has-error" : "has-success"?>">
							<?php
								$result[$field_name] = 1;
								echo form_dropdown($field_name, $GLOBALS['LIST_TYPE'], @$result[$field_name], 'id="list_type" class="form-control required" required')
							?>
							<label for="<?=$field_name;?>" ><?=$field_title;?>
								<span class="required">*</span>
							</label>
							<span class="help-block span_block">Select <?=$field_title;?></span>
						</div>
					</div> */?>
					<div class="col-md-5">
						<?php
							//echo '<pre>'; print_r($result); 
							$field_name  = "list_name";
							$field_title = "List Name";
						?>
						<div class="form-group form-md-line-input form-md-floating-label <?=(!empty(form_error($field_name))) ? "has-error" : "has-success"?>">
							<input type="text" class="form-control required" name="<?=$field_name;?>" id="<?=$field_name;?>" value="<?= set_value($field_name,@$result[$field_name]);?>" required />
							<label for="<?=$field_name;?>" ><?=$field_title;?>
								<span class="required">*</span>
							</label>
							<span class="help-block span_block">Enter <?=$field_title;?></span>
						</div>
					</div>
				</div>
				
				<?php
					$style = (@$result['list_type'] == 2) ? 'display:block' : 'display:none';
					$cls = (@$result['list_type'] == 2) ? 'required' : '';
					$disabled = (@$result['list_type'] == 2) ? '' : ' disabled="true"';
					
					@$list_details = json_decode(@$result['list_details']);
				?>
				<div id="emailListDiv" style="<?=$style?>">
					<div class="row mt-repeater-row">
						<div class="col-md-1"></div>
						<div class="col-md-5">
							<?php
								$field_name  = "from_email";
								$field_title = "Default From email address";
							?>
							<div class="form-group form-md-line-input form-md-floating-label <?=(!empty(form_error($field_name))) ? "has-error" : "has-success"?>">
								<input type="email" class="form-control <?=$cls?>" name="<?=$field_name;?>" id="<?=$field_name;?>" value="<?= set_value($field_name, @$list_details->{$field_name});?>" <?=$disabled?> <?=$cls?>/>
								<label for="<?=$field_name;?>" ><?=$field_title;?>
									<span class="required">*</span>
								</label>
								<span class="help-block span_block">Enter <?=$field_title;?></span>
							</div>
						</div>
						<div class="col-md-5">
							<?php
								$field_name  = "from_name";
								$field_title = "Default From name";
							?>
							<div class="form-group form-md-line-input form-md-floating-label <?=(!empty(form_error($field_name))) ? "has-error" : "has-success"?>">
								<input type="text" class="form-control <?=$cls?>" name="<?=$field_name;?>" id="<?=$field_name;?>" value="<?= set_value($field_name, @$list_details->{$field_name});?>" <?=$disabled?> <?=$cls?>/>
								<label for="<?=$field_name;?>" ><?=$field_title;?>
									<span class="required">*</span>
								</label>
								<span class="help-block span_block">Enter <?=$field_title;?></span>
							</div>
						</div>
					</div>
					<div class="row mt-repeater-row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<?php
								$field_name  = "subject";
								$field_title = "Default email subject";
							?>
							<div class="form-group form-md-line-input form-md-floating-label <?=(!empty(form_error($field_name))) ? "has-error" : "has-success"?>">
								<?php echo form_input(array('name' => $field_name, 'id' => $field_name, 'class' => "form-control textcounter ".$cls, 'data-text-limit' => "150"), set_value($field_name, @$list_details->{$field_name}), $disabled.' '.$cls)?>
								<label for="<?=$field_name;?>" ><?=$field_title;?>
									<span class="required">*</span>
								</label>
								<span class="help-block span_block">Enter <?=$field_title;?></span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row mt-repeater-row"><div class="col-md-12"><div class="form-group">&nbsp;</div></div></div>
			</div>
			
			<div class="margin-top-10">
				<div class="col-md-1"></div>
				<button type="submit" name="save_changes" class="btn green-meadow">Save</button>
				<a href="javascript:history.go(-1);" class="btn default">Cancel</a>
			</div>
		</form>
		
	</div>
</div>
<script type="application/javascript">
	$(function(){
		$('#list_type').focus();
		
		//TextCounter
		var loadTextCounter = function(){
			$('.textcounter').textcounter({
				max: $('.textcounter').attr('data-text-limit'),
				countDown: true,
				stopInputAtMaximum: true
			});
		}
		loadTextCounter();
		
		$('#list_type').on('change', function(){
			if($(this).val() == 2){
				$('#emailListDiv').show();
				$('#from_email').addClass('required'); $('#from_email').attr('disabled', false);
				$('#from_name').addClass('required'); $('#from_name').attr('disabled', false);
				$('#subject').addClass('required'); $('#subject').attr('disabled', false);
			}else{
				$('#emailListDiv').hide();
				$('#from_email').removeClass('required'); $('#from_email').attr('disabled', true);
				$('#from_name').removeClass('required'); $('#from_name').attr('disabled', true);
				$('#subject').removeClass('required'); $('#subject').attr('disabled', true);
			}
		});
	});
</script>