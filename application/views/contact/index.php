<?php echo form_open('', 'id="dataListing" name="dataListing"');?>
<input type="hidden" value="<?=$this->controller_url?>delete/multi" id="multi_delete" />
<div class="portlet light portlet-fit ">
	<div class="portlet-title">
		<div class="caption">
			<i class=" icon-layers font-green"></i>
			<span class="caption-subject font-green bold uppercase"><?=@$section_title?></span>
		</div>
		<div class="actions">
			<a class="btn green-sharp"  href="<?=$this->controller_url.'add'?>" title="Create List"><i class="fa fa-plus"></i> Create List</a>
			<a class="btn red" id="button-delete" href="javascript:void(0);" title="Delete"><i class="fa fa-trash"></i> Delete List</a>
			<a class="btn grey btn-default" title="Back" href="javascript:;" onclick="window.history.go(-1); return false;"><i class="icon-action-undo"></i> Back</a>
		</div>
	</div>
	<div class="portlet-body">
		
		<div class="note note-danger">
			<p class="block">You can't delete <strong>LIST</strong> that is already assigned to any Campaign.</p>
		</div>
		
		<!--STARTs SEARCH -->
		<div class="row">
			<div class="col-md-2">
				<div class="form-group form-md-line-input form-md-floating-label has-success">
					<?php
						$field_name = "list_name";
						$field_title = "List Name";
						echo form_input($field_name, (@$list_name ? $list_name : @$result->{$field_name})," id='".$field_name."' class='form-control' ");
					?>
					<label for="<?=$field_name;?>" ><?=$field_title;?></label>
					<span class="help-block span_block">Enter <?=$field_title;?></span>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group form-md-line-input has-success">
					<?php
						$field_name = "list_type";
						$field_title = "List Type";
						echo form_dropdown($field_name, $GLOBALS['LIST_TYPE'], (@$list_type ? @$list_type : @$result->{$field_name}),'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
					?>
					<label for="<?=$field_name;?>" ><?=$field_title;?></label>
					<span class="help-block span_block">Select <?=$field_title;?></span>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group form-md-line-input has-success">
					<?php
						$field_name = "status";
						$field_title = "Status";
						$options    = $GLOBALS['STATUS'];
						echo form_dropdown($field_name, $options, (@$status ? @$status : @$result->{$field_name}),'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
					?>
					<label for="<?=$field_name;?>" ><?=$field_title;?></label>
					<span class="help-block span_block">Select <?=$field_title;?></span>
				</div>
			</div>
			<div class="col-sm-1">
				<div class="form-group">
					<button type="submit" class="btn green form-control btn-filter-submit">Search</button>
				</div>
			</div>
			<div class="col-sm-2 pull-right">
				<?php
					$model = $this->{$this->model};
					
					//Pagination Links - TOP
					echo"<tr><th colspan='".(count($model->list_data_columns)+2)."' class='ci_pagination text-right'>";
					echo '<div><p style="margin-bottom: 0;margin-top: 0;" class="todo-head">'. (($result['total'] > $limit) ? 'Showing '.($offset+1).' to '.($offset+$limit).' of '.$result['total'].' Records' : 'Total '.$result['total'].' Records').'</p>'. form_dropdown('limit',$GLOBALS['LIMIT'],set_value('limit',$limit), 'class="form-control" id="limit" onchange="submitFormPagination();" style="display: inline-block;margin: 10px 10px; float: right;" ') . $result['links'].'</div>';
					echo"</th></tr>";
				?>
			</div>
		</div>
		<!-- END Search -->

		<div class="table-scrollable">
			<table class="table table-striped table-bordered table-advance table-hover">
				<thead>
					<tr>
						<th width="1%">
							<div class="input-group">
								<div class="icheck-list">
									<label>
										<input type="checkbox" class="icheck checkall" id="checkall" value="all" />
									</label>
								</div>
							</div>
						</th>
						<?php
						if(isset($model->list_data_columns)){
							foreach($model->list_data_columns as $db=>$col){
								if(in_array($db, array('id','uid','list_details'))) continue;
								
								$cls = (in_array($db, array('contacts'))) ? 'text-right' : 'text-left';
								echo "<th class='".$cls."'><strong>".$col."</strong></th>";
							}
						}
						?>
						<th class="text-center" width="5%"><strong>List ID</strong></th>
						<th class="text-right" width="25%"><strong>Action</strong></th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i=1;
						if(isset($result) && !empty($result['total'])){
							foreach($result['result'] as $row){

								$disabled = (in_array($row['id'],$used_campaign_lists)) ? 'disabled' : '';
								$display_none = (in_array($row['id'],$used_campaign_lists)) ? 'display:none' : '';

								echo "<tr>";
								echo '<td>
												<div class="icheck-list">
													<label>
														<input type="checkbox" '.$disabled.' name="ids[]" class="icheck chkbox" value="'.encrypt_decrypt('encrypt',$row[$model->primary_key]).'" />
													</label>
												</div>
											</td>';
									foreach($model->list_data_columns as $db=>$col){
										if(in_array($db, array('id', 'uid', 'list_details'))) continue;
										
										$cls = (in_array($db, array('contacts'))) ? 'text-right' : 'text-left';
										if ($db=="status") @$a = '<input name="status_'.$row['id'].'" id="status_'.$row['id'].'" type="checkbox" class="make-switch"  '.(($row[$db] == 1)?'checked':'').' data-on-text="<i class=\'fa fa-check\'></i>" data-off-text="<i class=\'fa fa-times\'></i>" data-size="mini" data-table="'.$this->model_table.'" data-field="status" data-id="'.$row['id'].'"> &nbsp; <span class="status_'.$row['id'].' '.(($row[$db] == 1)?'font-green-sharp':'font-red-mint').'">'.$GLOBALS['STATUS_X'][$row[$db]] . '</span>';
										elseif ($db=="uid") @$a = $USERS[$row[$db]];
										elseif ($db=="list_type") @$a = $GLOBALS['LIST_TYPE'][$row[$db]];
										elseif ($db=="c_date") @$a = date('d-m-Y H:i:s',strtotime($row[$db]));
										else @$a = $row[$db];
										echo "<td class='".$cls."'>".$a."</td>";
									}

									echo'<td class="text-right"><code>'.encrypt_decrypt('encrypt',$row['id']).'</code></td>';
									echo'<td class="text-right">'.
										'<a class="btn btn-circle btn-icon-only green-meadow btn-outline"  href="'.$this->controller_url.'add/'.encrypt_decrypt('encrypt',$row[$model->primary_key]).'" title="Edit List"><i class="fa fa-edit"></i></a>'.
										'<a class="btn btn-circle btn-icon-only red btn-outline deleteItem" style="'.$display_none.'" href="'.$this->controller_url.'delete/'.encrypt_decrypt('encrypt',$row[$model->primary_key]).'" title="Delete List"><i class="fa fa-trash" style="font-size: 18px;"></i></a>'.
										'<span style="display: inline-block;margin-right: 5px;padding: 5px;"></span>'.
										'<a class="btn btn-circle btn-icon-only grey-mint btn-outline" href="'.base_url().'lists/get_form/'.encrypt_decrypt('encrypt',$row[$model->primary_key]).'" title="Get List Form"><i class="fa fa-list-alt"></i></a>'.
										'<a class="btn btn-circle btn-icon-only green-meadow btn-outline" href="'.base_url().'subscribers/add/'.encrypt_decrypt('encrypt',$row[$model->primary_key]).'" title="Add Subscriber to this List"><i class="fa fa-plus-circle"></i></a>'.
										'<a class="btn btn-circle btn-icon-only blue btn-outline" href="'.base_url().'subscribers/import_subs/'.encrypt_decrypt('encrypt',$row[$model->primary_key]).'" title="Import Subscribers to this List"><i class="fa fa-upload"></i></a>'.
										'<a class="btn btn-circle btn-icon-only purple btn-outline" href="'.base_url().'subscribers/index/'.encrypt_decrypt('encrypt',$row[$model->primary_key]).'" title="List Subscribers"><i class="fa fa-list"></i></a>'.
										'<a class="btn btn-circle btn-icon-only red btn-outline emptyItem"  href="'.$this->controller_url.'emptyList/'.encrypt_decrypt('encrypt',$row[$model->primary_key]).'" title="Empty List - Remove all Subscribers"><i class="fa fa-battery-empty" style="font-size: 18px;"></i></a>'.
									'</td>';
								echo "</tr>";
								$i++;
							}
							
							//Pagination Links - BUTTOM
							echo"<tr><td colspan='".(count($model->list_data_columns)+2)."' class='ci_pagination'>";
							echo '<div>'.$result['links'].'</div>';
							echo"</td></tr>";
						}else{
							echo '<tr><td colspan="'.(count($model->list_data_columns)+2).'">No Data Found.</td></tr>';
						}
					?>
				</tbody>
			</table>
		</div>
		
	</div>
</div>
</form>