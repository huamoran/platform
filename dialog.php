<!DOCTYPE html>
<html>
<head>
	<title>Sandbox - jQuery DialogExtend</title>
	<meta charset=utf-8 />
	<link class="jsbin" href="http://code.jquery.com/ui/1.8.22/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
	<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script class="jsbin" src="http://code.jquery.com/ui/1.8.22/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/jquery.dialogextend.1_0_1.js"></script>
	<style id="jsbin-css">
	body { color: #333333; font-family: Times, Helvetica, Arial; font-size: 16px; }
	.ui-dialog { font-size: 12px; }
	/***** HEADER *****/
	header { background-color: #f0f0f0; border-radius: 1em; box-shadow: inset 0 0 10px gray; padding: 1em 1.5em 1.5em 1.5em; }
	header h1 { margin: 0 0 0.5em 0; }
	header ul { margin: 1em 0 0 0; }
	/***** CONTENT *****/
	section fieldset { margin: 5px; width: 200px; }
	section label { cursor: pointer; }
	#config-icon .wrapper { clear: both; }
	#config-icon ins { float: left; margin: 0 5px 0 0; }
	#config-icon label { float: left; }
	#config-icon select { float: right; width: 100px; }
	#config-method button { width: 48%; }
	/***** FOOTER *****/
	footer { clear: both; padding-top: 2em; }
	footer button { background-color: #e0e0e0; border: none; border-radius: 1em; box-shadow: 0 5px 5px silver; cursor: pointer; font-size: 300%; padding: 10px 0 10px 0; text-align: center; width: 100%; }
	footer button:hover { background-color: #d0d0d0; box-shadow: 0 5px 5px #aaaaaa; }
	footer button:active { box-shadow: 0 4px 4px #aaaaaa; position: relative; top: 1px; }
	</style>
</head>
<body>
<header>
	<h1>jQuery DialogExtend Test Tool</h1>
	<ul>
		<li><strong>DialogExtend</strong> {version 1.0.1}</li>
		<li><strong>jQuery</strong> {version 1.7.2}</li>
		<li><strong>jQueryUI</strong> {version 1.8.22}</li>
	</ul>
</header>
<section>
	<form id="my-form">
		<h2>Configuration</h2>
		<div style="float: left;">
			<fieldset id="config-button">
				<legend>Buttons</legend>
				<div>
					<input type="checkbox" id="button-close" checked="checked" />
					<label for="button-close">Close button</label>
				</div>
				<div>
					<input type="checkbox" id="button-maximize" checked="checked" />
					<label for="button-maximize">Maximize button</label>
				</div>
				<div>
					<input type="checkbox" id="button-minimize" checked="checked" />
					<label for="button-minimize">Minimize button</label>
				</div>
			</fieldset>
			<fieldset id="config-icon">
				<legend>Icons</legend>
				<div class="wrapper">
					<ins class="ui-state-default ui-corner-all"></ins>
					<label for="icon-close">Close:</label>
					<select id="icon-close" name="icon" rel="close">
						<option value="ui-icon-closethick">(default)</option>
						<option value="ui-icon-close">ui-icon-close</option>
						<option value="ui-icon-cancel">ui-icon-cancel</option>
						<option value="ui-icon-circle-close">ui-icon-circle-close</option>
					</select>
				</div>
				<div class="wrapper">
					<ins class="ui-state-default ui-corner-all"></ins>
					<label for="icon-maximize">Maximize:</label>
					<select id="icon-maximize" name="icon" rel="maximize">
						<option value="ui-icon-extlink">(default)</option>
						<option value="ui-icon-carat-1-ne">ui-icon-carat-1-ne</option>
						<option value="ui-icon-arrow-4-diag">ui-icon-arrow-4-diag</option>
						<option value="ui-icon-circle-plus">ui-icon-circle-plus</option>
					</select>
				</div>
				<div class="wrapper">
					<ins class="ui-state-default ui-corner-all"></ins>
					<label for="icon-minimize">Minimize:</label>
					<select id="icon-minimize" name="icon" rel="minimize">
						<option value="ui-icon-minus">(default)</option>
						<option value="ui-icon-carat-1-sw">ui-icon-carat-1-sw</option>
						<option value="ui-icon-arrowstop-1-s">ui-icon-arrowstop-1-s</option>
						<option value="ui-icon-circle-minus">ui-icon-circle-minus</option>
					</select>
				</div>
				<div class="wrapper">
					<ins class="ui-state-default ui-corner-all"></ins>
					<label for="icon-restore">Restore:</label>
					<select id="icon-restore" name="icon" rel="restore">
						<option value="ui-icon-newwin">(default)</option>
						<option value="ui-icon-carat-2-n-s">ui-icon-carat-2-n-s</option>
						<option value="ui-icon-refresh">ui-icon-refresh</option>
						<option value="ui-icon-circle-arrow-n">ui-icon-circle-arrow-n</option>
					</select>
				</div>
			</fieldset>
		</div>
		<div style="float: left;">
			<fieldset id="config-dblclick">
				<legend>Double-click</legend>
				<div>
					<input type="radio" name="dblclick" id="dblclick-default" value="" />
					<label for="dblclick-default">(none)</label>
				</div>
				<div>
					<input type="radio" name="dblclick" id="dblclick-collapse" value="collapse" checked="checked" />
					<label for="dblclick-collapse">collapse</label>
				</div>
				<div>
					<input type="radio" name="dblclick" id="dblclick-maximize" value="maximize" />
					<label for="dblclick-maximize">maximize</label>
				</div>
				<div>
					<input type="radio" name="dblclick" id="dblclick-minimize" value="minimize" />
					<label for="dblclick-minimize">minimize</label>
				</div>
			</fieldset>
			<fieldset id="config-titlebar">
				<legend>Title bar</legend>
				<div>
					<input type="radio" name="titlebar" id="titlebar-default" value="" checked="checked" />
					<label for="titlebar-default">(default)</label>
				</div>
				<div>
					<input type="radio" name="titlebar" id="titlebar-none" value="none" />
					<label for="titlebar-none">none</label>
				</div>
				<div>
					<input type="radio" name="titlebar" id="titlebar-transparent" value="transparent" />
					<label for="titlebar-transparent">transparent</label>
				</div>
			</fieldset>
		</div>
		<div style="float: left;">
			<fieldset id="config-event">
				<legend>Events</legend>
				<div>
					<input type="checkbox" name="event" id="event-load" rel="load" checked="checked" />
					<label for="event-load">load</label>
				</div>
				<div>
					<input type="checkbox" name="event" id="event-b4collapse" rel="beforeCollapse" checked="checked" />
					<label for="event-b4collapse">beforeCollapse</label>
				</div>
				<div>
					<input type="checkbox" name="event" id="event-b4maximize" rel="beforeMaximize" checked="checked" />
					<label for="event-b4maximize">beforeMaximize</label>
				</div>
				<div>
					<input type="checkbox" name="event" id="event-b4minimize" rel="beforeMinimize" checked="checked" />
					<label for="event-b4minimize">beforeMinimize</label>
				</div>
				<div>
					<input type="checkbox" name="event" id="event-b4restore" rel="beforeRestore" checked="checked" />
					<label for="event-b4restore">beforeRestore</label>
				</div>
				<div>
					<input type="checkbox" name="event" id="event-collapse" rel="collapse" checked="checked" />
					<label for="event-collapse">collapse</label>
				</div>
				<div>
					<input type="checkbox" name="event" id="event-maximize" rel="maximize" checked="checked" />
					<label for="event-maximize">maximize</label>
				</div>
				<div>
					<input type="checkbox" name="event" id="event-minimize" rel="minimize" checked="checked" />
					<label for="event-minimize">minimize</label>
				</div>
				<div>
					<input type="checkbox" name="event" id="event-restore" rel="restore" checked="checked" />
					<label for="event-restore">restore</label>
				</div>
			</fieldset>
		</div>
		<div style="float: left;">
			<fieldset id="config-method">
				<legend>Methods <small><em>(apply to last dialog)</em></small></legend>
				<div>
					<button type="button" id="method-collapse">collapse</button>
					<button type="button" id="method-maximize">maximize</button>
					<button type="button" id="method-minimize">minimize</button>
					<button type="button" id="method-restore">restore</button>
					<button type="button" id="method-state">state</button>
				</div>
			</fieldset>
			<fieldset id="config-dialog">
				<legend>Dialog</legend>
				<div>
					<input type="checkbox" id="is-modal" />
					<label for="is-modal">Modal Dialog</label>
				</div>
				<div>
					<input type="checkbox" id="button-cancel" checked="checked" />
					<label for="button-cancel">Cancel Button</label>
				</div>
				<div>
					<input type="checkbox" id="is-resizable" checked="checked" />
					<label for="is-resizable">Resizable</label>
			</fieldset>
		</div>
	</form>
</section>
<br clear="both" />
<footer>
	<button type="button" id="my-button">Click Me</button>
	<div style="padding-top: 100em;">(demo dialogExtend features with page scroll)</div>
</footer>
<script>
$(function(){

	// preview icon
	$("#config-icon select")
		.change(function(){
			var icon = "<span class='ui-icon "+$(this).val()+"'></span>";
			$(this).parents(".wrapper").find("ins").html(icon);
		})
		.trigger("change");


	// click to open dialog
	$("#my-button").click(function(){
		//dialog options
		var dialogOptions = {
			"title" : "dialog@" + new Date().getTime(),
			"width" : 400,
			"height" : 300,
			"modal" : $("#is-modal").is(":checked"),
			"resizable" : $("#is-resizable").is(":checked"),
			"close" : function(){ $(this).remove(); }
		};
		if ( $("#button-cancel").is(":checked") ) {
			dialogOptions.buttons = { "Cancel" : function(){ $(this).dialog("close"); } };
		}
		// dialog-extend options
		var dialogExtendOptions = {
			"close" : $("#button-close").is(":checked"),
			"maximize" : $("#button-maximize").is(":checked"),
			"minimize" : $("#button-minimize").is(":checked"),
			"dblclick" : $("#my-form [name=dblclick]:checked").val() || false,
			"titlebar" : $("#my-form [name=titlebar]:checked").val() || false
		};
		$("#my-form [name=icon]").each(function(){
			if ( $(this).find("option:selected").html() != "(default)" ) {
				dialogExtendOptions.icons = dialogExtendOptions.icons || {};
				dialogExtendOptions.icons[$(this).attr("rel")] = $(this).val();
			}
		});
		$("#my-form [name=event]").each(function(){
			if ( $(this).is(":checked") ) {
				dialogExtendOptions.events = dialogExtendOptions.events || {};
				dialogExtendOptions.events[$(this).attr("rel")] = function(evt, dlg) {
					$(dlg).prepend(evt.type+"."+evt.handleObj.namespace+"@"+new Date().getTime()+"<br />");
				};
			}
		});
		// open dialog
		$("<div />").dialog(dialogOptions).dialogExtend(dialogExtendOptions);
	});


	// click to invoke method
	$("#config-method button").click(function(){
		var command = $(this).text();
		var dialog = $(".ui-dialog:last").find(".ui-dialog-content");
		if ( $(dialog).length ) {
			if ( command == 'state' ) {
				alert( $(dialog).dialogExtend(command) );
			} else {
				$(dialog).dialogExtend(command);
			}
		}
	});

});
</script>
</body>
</html>