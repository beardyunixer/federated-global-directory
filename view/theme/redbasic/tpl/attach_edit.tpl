<form action="filestorage/{{$channelnick}}/{{$file.id}}/edit" method="post" >
	<input type="hidden" name="channelnick" value="{{$channelnick}}" />
	<input type="hidden" name="filehash" value="{{$file.hash}}" />
	<input type="hidden" name="uid" value="{{$uid}}" />
	<input type="hidden" name="fileid" value="{{$file.id}}" />
	{{if !$isadir}}{{include file="field_checkbox.tpl" field=$notify}}{{/if}}
	{{if $isadir}}{{include file="field_checkbox.tpl" field=$recurse}}{{/if}}
	<div id="attach-edit-tools-share" class="btn-group form-group">
		{{if !$isadir}}
		<a href="/rpost?attachment=[attachment]{{$file.hash}},{{$file.revision}}[/attachment]" id="attach-btn" class="btn btn-default btn-xs" title="{{$attach_btn_title}}">
			<i class="icon-share jot-icons"></i>
		</a>
		{{/if}}
		<button id="link-btn" class="btn btn-default btn-xs" type="button" onclick="openClose('link-code');" title="{{$link_btn_title}}">
			<i class="icon-link jot-icons"></i>
		</button>
	</div>
	<div id="attach-edit-perms" class="btn-group pull-right">
		<button id="dbtn-acl" class="btn btn-default btn-xs" data-toggle="modal" data-target="#aclModal" title="{{$permset}}" onclick="return false;">
			<i id="jot-perms-icon" class="icon-{{$lockstate}} jot-icons"></i>
		</button>
		<button id="dbtn-submit" class="btn btn-primary btn-xs" type="submit" name="submit">
			{{$submit}}
		</button>
	</div>
	{{$aclselect}}

	<div id="link-code" class="form-group">
		<label for="">{{$cpldesc}}</label>
		<input type="text" class="form-control" id="linkpasteinput" name="cutpasteextlink" value="{{$cloudpath}}" onclick="this.select();"/>
	</div>
</form>

