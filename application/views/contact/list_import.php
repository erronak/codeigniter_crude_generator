<style>
		/*******************************************/
		.header-cell .state-error  {
			text-align: center;
			font-weight: normal;
		}
		#map-columns {
		  overflow: auto;
		  padding-left:10px;
		}
		.below12 {
		  margin-bottom: 12px !important;
		}
		/*.relative {
		  position: relative;
		}*/
		#import-map th.map-activecol {
		  border-top-color: #7FCE75;
		}
		#import-map .map-activecol {
		  border-bottom: none;
		  color: #484848;
		  text-align: left;
		  border-color: #7FCE75;
		}
		#import-map th {
		  height: 130px;
		  min-width: 200px;
		  text-align: middle;
		  vertical-align: middle;
		  border-top: 2px solid #e0e0e0;
		}
		#import-map td, #import-map th {
		  font-size: 12px;
		  border-left: 2px solid #e0e0e0;
		  border-right: 2px solid #e0e0e0;
		  padding: 6px 6px;
		}
		.table-bordered, td, th {
		  border-radius: 0!important;
		}
		table {
		  border-collapse: collapse;
		  border-spacing: 0;
		}
		#import-map th.unnamed {
		  border-top-color: #ee836e;
		}
		#import-map .unnamed {
		  border-bottom: none;
		  border-color: #ee836e;
		}
		select {
		  padding: 3px 4px;
		  height: 30px;
		}
		.form-control, select {
		  border-radius: 0;
		  -webkit-box-shadow: none!important;
		  box-shadow: none!important;
		  color: #858585;
		  background-color: #fff;
		  border: 1px solid #d5d5d5;
		}
		.widget-main {
		  padding: 12px;
		}
		.btn-xs {
		  border-width: 3px;
		}
		.btn-group-xs>.btn, .btn-xs {
		  padding: 1px 5px;
		  font-size: 12px;
		  line-height: 1.5;
		  border-radius: 3px;
		}
		tr {
		  display: table-row;
		  vertical-align: inherit;
		  border-color: inherit;
		}
		#import-map td.map-activecol {
		  background-color: #ADE2A6;
		}
		#import-map td.unnamed {
		  background-color: #fbe3e4;
		}
		#import-map td {
		  border-top: 1px dotted #e0e0e0;
		}
		/*td, th {
		  display: table-cell;
		  vertical-align: inherit;
		}*/
		table {
		  background-color: transparent;
		}
		tbody {
		  display: table-row-group;
		  vertical-align: middle;
		  border-color: inherit;
		}
		#import-map tbody tr:last-child .map-activecol {
		  border-bottom-color: #7FCE75;
		}
		#import-map tbody tr:last-child td {
		  border-bottom: 2px solid #e0e0e0;
		}
		#import-map tbody tr:last-child .unnamed {
		  border-bottom-color: #ee836e;
		}
		#import-map tbody tr:last-child td {
		  border-bottom: 2px solid #e0e0e0;
		}
		/*******************************************/
		.form-ipmort .state-error + em {
			display: block!important;
			margin-top: 6px;
			padding: 0 3px;
			font-family: Arial, Helvetica, sans-serif;
			font-style: normal;
			line-height: normal;
			font-size: 0.85em;
			color: #DE888A;
		}
		em {
			display: block!important;
			margin-top: 6px;
			padding: 0 3px;
			font-family: Arial, Helvetica, sans-serif;
			font-style: normal;
			line-height: normal;
			font-size: 0.85em;
			color: #DE888A;
		}
		.form-ipmort input[type=file].state-error, .form-ipmort input[type=text].state-error{
			background: #FEE9EA;
			border-color: #DE888A;
		}
</style>
<div class="portlet light portlet-fit ">
	<div class="portlet-title">
		<div class="caption">
			<i class=" icon-layers font-green"></i>
			<span class="caption-subject font-green bold uppercase"><?=@$section_title?></span>
		</div>
		<div class="actions">
				
		</div>
	</div>
	<div class="portlet-body">

		<?php if($action=="uploaded") { ?>
		<div class="note note-success">
			<h4 class="block"><i class="fa fa-thumbs-o-up"></i> Great ! Just one step short of starting your campaign.</h4>
			<p> You are just one step ahead of starting your campaign. Upload list and start your campaign.</p>
		</div>
		<?php } ?>
		
		<?php echo form_open('', array( 'class'=>'form-ipmort', 'role'=>'form', 'enctype' => 'multipart/form-data'));?>
			<input type="hidden" name="form_post" value="1" />
			<?php
				if(@$customized == 1){
					echo form_hidden('flag',0);
					echo form_hidden('custom_file_format',0);
					echo form_hidden('customized',1);
					echo form_hidden('list_id', @$list_id);
					echo form_hidden('file_path',@$file_path);
					echo form_hidden('org_file_name',@$org_file_name);
					
					echo form_input(array('name' => 'columns', 'type'=>'hidden', 'id' =>'total_columns', 'value'=> count(@$file_header)));  
					$current_header = @$file_header;
					
					//echo '<pre>'; print_r($current_header);
			?>
					<div class="table-scrollable">
					  <table id="import-map" class="table table-striped table-bordered table-hover">
						<tbody>
						  <tr>
								<?php
									if(is_array($current_header) && !empty($current_header)){
										$cls = array();
										$int = 0;
										//echo '<pre>'; print_r($current_header);
										foreach($current_header as $k => $h){
											//echo strtolower(@$GLOBALS['CUSTOM_HEADER'][$h]).'=='.strtolower($h).'<br>';
											$cls[$k] = ((strtolower(@$GLOBALS['CUSTOM_LIST_DATA_HEADER'][$h]) == strtolower($h)) ? 'map-activecol' : 'unnamed');
								?>
											<th class="header-cell <?=((strtolower(@$GLOBALS['CUSTOM_LIST_DATA_HEADER'][$h]) == strtolower($h)) ? 'map-activecol' : 'unnamed')?> cust_hdr_cln_<?=$int?>" id="header-<?=$int?>">
												<?php
													$matched_h = 'skip'; $notmatched = $org_file_header[$k];
													if(strtolower(@$GLOBALS['CUSTOM_LIST_DATA_HEADER'][$h]) == strtolower($h)){
														$matched_h = $h;
													}
													echo '<center class="field">'.form_dropdown('custom_header['.$k.']', $GLOBALS['CUSTOM_LIST_DATA_HEADER'], $matched_h, 'class="required custom_header" id="col-name-'.$int.'" map="'.$h.'" required').'</center>';

													echo '<center><br />'.$notmatched.'</center>';
												?>
											</th>
								<?php
											$int++;
										}
									}
								?>
						  </tr>
							<?php
								$int = 0;
								if(is_array($current_header) && !empty($current_header)){
									foreach($sheetData as $k => $val){
							?>
									  <tr>
											<?php
												foreach($val as $kk => $vv){
											?>
												<td class="<?=$cls[$kk]?>  cust_hdr_cln_<?=$int?> "><?=trim($vv)?></td>
											<?php $int++;}?>
									  </tr>
							<?php
										$int = 0;
									}
								}
							?>
						</tbody>
					  </table>
					</div>
					<div class="display_error" style="margin-top: 10px;"><?=@$display_error;?></div>
					<br />
					<div class="margin-top-10">
						<button type="submit" name="save_changes" class="btn green-meadow">Import List Data</button>
						<a href="javascript:history.go(-1);" class="btn default">Cancel</a>
					</div>
			<?php
				}else{
					$flaginfo = array(
								'up_flag' => TRUE,
								'spred_data' => '',
								'org_file_name' => '',
								'file_path'  => '',
					);
					$this->session->set_userdata($flaginfo);
					echo form_hidden('flag',1);
					echo form_hidden('customized',0);
					echo form_hidden('custom_file_format',1);
			?>
				<div class="form-group mt-repeater">
					<div data-repeater-list="group-c">
						<div>

							<div class="row mt-repeater-row ">
								<?php if($action=="uploaded") {
								echo form_hidden('list_id',$list_id_encrypted);
								} else {?>
								<div class="col-md-6">
									<?php
									$field_name  = "list_id";
									$field_title = "Select List";
									?>
									<div class="form-group <?php if(!empty(form_error($field_name))){ echo "has-error";} ?>">
										<label><?=$field_title;?></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-object-group"></i></span>
											<?php
												$options    = array('' => 'Select List') + (is_array($get_lists) ? $get_lists : array());
												echo form_dropdown($field_name,$options,set_value($field_name,@$result->{$field_name}),'class="form-control required" required');
											?>
										</div>
									</div>
								</div>
								<?php } ?>
								<div class="col-md-6">
									<?php
									$field_name  = "smtp_file";
									$field_title = "Upload Spreadsheet";
									?>
									<div class="form-group <?php if(!empty(form_error($field_name))){ echo "has-error";} ?>">
										<label><?=$field_title?></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-file-excel-o"></i></span>
											<input type="file" class="form-control required" placeholder="<?=$field_title?>" name="<?=$field_name?>" value="<?php echo set_value($field_name,@$result->$field_name);?>" accept=".csv" required>
										</div>
									</div>
									<p class="help-block">Allowed file format : CSV (Maximum file size : 5MiB).</p>
								</div>
							</div>

						</div>
					</div>

				</div>
				<div class="margin-top-10">
					<button type="submit" name="save_changes" class="btn green-meadow">Next</button>
					<a href="javascript:history.go(-1);" class="btn default">Cancel</a>
				</div>
			<?php }?>
		</form>

	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#group_id').focus();
		var selected_arr = new Array();

		$('.custom_header').on('change', function(){
			if($(this).val() != 'skip' && $(this).val() != ''){
				var xx = $(this).prop('id');
				var id = xx.split("-");
				var header = $(this).val();
				var total = Number($("#total_columns").val());
				//alert(id +'='+ header +'='+ total);
				for(var i =0; i <=total; i++){
					if($("#col-name-"+i).val() == header){  //  && i == id[2]
						//$('.display_error').show();
						//$('.display_error').html('<code>'+header+" is already used for other column!<code>");
						$("#col-name-"+i).prop('value', "skip");
						$(".cust_hdr_cln_"+i).removeClass('map-activecol').addClass('unnamed');
						selected_arr[i] = "skip";
						//return false;
					}
					$('.display_error').hide();
				}
				$(this).prop('value', header);
				$(this).parent('center').parents('th').removeClass('unnamed').addClass('map-activecol');
				$(".cust_hdr_cln_"+id[2]).removeClass('unnamed').addClass('map-activecol');
				selected_arr[id[2]] = $(this).val();
			}else{ //if($(this).val() == 'skip'){
				var xx = $(this).prop('id');
				var id = xx.split("-");
				$(".cust_hdr_cln_"+id[2]).removeClass('map-activecol').addClass('unnamed');
				$("#col-name-"+id[2]+'  option[value="skip"]').prop('selected', true);
			}
		});
		
	});
</script>