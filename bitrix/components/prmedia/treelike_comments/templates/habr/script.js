var last_active_element;

function getSelectedText() {
	if (window.getSelection) {
		return window.getSelection().toString();
	}
	else if (document.getSelection) {
		return document.getSelection();
	}
	else if (document.selection) {
		return document.selection.createRange().text;
	}
}


function encloseSelection(prefix, suffix) {

	textarea = document.getElementById("TEXT");
	textarea.focus();
	var start, end, sel, scrollPos, subst;
	if (document.selection != undefined) {
		sel = document.selection.createRange().text;
	} else if (textarea.setSelectionRange != undefined) {
		start = textarea.selectionStart;
		end = textarea.selectionEnd;
		scrollPos = textarea.scrollTop;
		sel = textarea.value.substring(start, end);
	}
	if (sel.match(/ $/)) { // exclude ending space char, if any
		sel = sel.substring(0, sel.length - 1);
		suffix = suffix + " ";
	}
	subst = prefix + sel + suffix;
	if (document.selection != undefined) {
		var range = document.selection.createRange().text = subst;
		textarea.caretPos -= suffix.length;
	} else if (textarea.setSelectionRange != undefined) {
		textarea.value = textarea.value.substring(0, start) + subst +
						textarea.value.substring(end);
		if (sel) {
			textarea.setSelectionRange(start + subst.length, start + subst.length);
		} else {
			textarea.setSelectionRange(start + prefix.length, start + prefix.length);
		}
		textarea.scrollTop = scrollPos;
	}
}


function insertAtCursor(val) {

	obj = document.getElementById("TEXT");

	if (document.selection) {
		obj.focus();
		sel = document.selection.createRange();
		sel.text = val;
	} else
	if (obj.selectionStart || obj.selectionStart == '0') {
		var startPos = obj.selectionStart;
		var endPos = obj.selectionEnd;
		obj.value = obj.value.substring(0, startPos) + val + obj.value.substring(endPos, obj.value.length);
	} else
		obj.value += val;
	return false;
}

function str_replace(search, replace, subject) {
	return subject.split(search).join(replace);
}

function scrollToComment(comment_id, to_moderate)
{
	var comment_id = '#comment_' + comment_id;
	if (to_moderate == 1 || jQuery(comment_id).length <= 0) {
		jQuery('html,body').animate({scrollTop: jQuery('.messages').offset().top}, 100);
	} else {
		jQuery('html,body').animate({scrollTop: jQuery(comment_id).offset().top}, 100);
	}
}

function DeleteComment(comment_id, has_subsidiaries)
{

	var DivID = jQuery('#comment_' + comment_id);
	var delete_single_element = 'D_ID=' + comment_id;
	var delete_all_elements = 'ALL_ID=' + comment_id;

	if (has_subsidiaries == 1)
	{
		if (confirm(CONFIRMATION_MULTI))
		{
			jQuery.ajax({
				type: "POST",
				url: "/bitrix/components/prmedia/treelike_comments/ajax_actions.php",
				data: delete_all_elements,
				cache: false,
				success: function()
				{
					DivID.html(DEL_SUCCESS_MULTI);
				}

			});
		}
	}
	else
	{
		if (confirm(CONFIRMATION_SINGLE))
		{
			jQuery.ajax({
				type: "POST",
				url: "/bitrix/components/prmedia/treelike_comments/ajax_actions.php",
				data: delete_single_element,
				cache: false,
				success: function()
				{
					DivID.html(DEL_SUCCESS_SINGLE);
				}

			});
		}
	}

}

function ReplyToComment(id)
{
	jQuery("a#comment_" + id).fadeOut(300);
	jQuery("#leave_comment_link").show();
	jQuery(".comment_item_reply_link").show();
	jQuery(".modify-link").show();

	jQuery("#new_comment_form").appendTo("#reply_to_" + id).fadeIn(500);
	jQuery("#new_comment_form input[name=PARENT_ID]").val(id);
	jQuery("#new_comment_form input[name=update_comment_id]").val(0);
	jQuery("#new_comment_form [name=COMMENT]").val("");

	jQuery(".update_comment_info").hide();
	jQuery(".add_comment_info").show();

	// focus on comment field
	jQuery("#TEXT").focus();
	if ($('.blured').length) {
		jQuery('.blured').focus();
	}
}

function UpdateComment(id)
{
	jQuery("a#update_comment_" + id).fadeOut(300);
	jQuery("#leave_comment_link").show();
	jQuery(".comment_item_reply_link").show();
	jQuery(".modify-link").show();

	jQuery("#new_comment_form").appendTo("#reply_to_" + id).fadeIn(500);
	jQuery("#new_comment_form input[name=PARENT_ID]").val(id);
	jQuery("#new_comment_form input[name=update_comment_id]").val(id);

	var commentText = jQuery(".comment_hidden_content_" + id).text();
	jQuery("#new_comment_form [name=COMMENT]").val(commentText);

	jQuery(".add_comment_info").hide();
	jQuery(".update_comment_info").show();

	// focus on comment field
	jQuery("#TEXT").focus();
	if ($('.blured').length) {
		jQuery('.blured').focus();
	}
}

function SaveData(id)
{
	jQuery("#leave_comment_link").show();
	jQuery(".comment_item_reply_link").show();
	ReplyToComment(id);
	jQuery("#new_comment_form input[name=PARENT_ID]").val(id);

	scrollToComment(id, 0);

}


function Activate(comment_id, SendingAfterActivate, send_after_answer, send_after_mention)
{
	var activate_comment = 'ACTIVATE_ON=' + comment_id;
	if (SendingAfterActivate == 'Y')
		SendingAfterActivate = 1;
	else
		SendingAfterActivate = 0;

	if (send_after_answer == 'Y')
		send_after_answer = 1;
	else
		send_after_answer = 0;

	if (send_after_mention == 'Y')
		send_after_mention = 1;
	else
		send_after_mention = 0;

	var sendingAfterActivate_config = 'SENDING=' + SendingAfterActivate;

	var DivID = jQuery('#comment_' + comment_id);
	var aActivateID = jQuery('#activate_' + comment_id);

	jQuery.ajax({
		type: "POST",
		url: "/bitrix/components/prmedia/treelike_comments/ajax_actions.php",
		data: activate_comment,
		cache: false,
		success: function()
		{
			aActivateID.html('');
			DivID.css('background', 'none');
		}

	});

	if (SendingAfterActivate == 1)
	{
		jQuery.ajax({
			type: "POST",
			url: "/bitrix/components/prmedia/treelike_comments/ajax_actions.php",
			data: {'SENDING': SendingAfterActivate, 'ACTIVATE_ON': comment_id},
			cache: false,
			success: function() {
			}

		});
	}

	if (send_after_answer == 1)
	{
		jQuery.ajax({
			type: "POST",
			url: "/bitrix/components/prmedia/treelike_comments/ajax_actions.php",
			data: {'SEND_AFTER_ANSWER': send_after_answer, 'COMMENT_ID': comment_id},
			cache: false,
			success: function() {
			}

		});
	}

	if (send_after_mention == 1)
	{
		jQuery.ajax({
			type: "POST",
			url: "/bitrix/components/prmedia/treelike_comments/ajax_actions.php",
			data: {'SEND_AFTER_MENTION': send_after_mention, 'COMMENT_ID': comment_id},
			cache: false,
			success: function() {
			}

		});
	}
}

function VoteUp(comment_id)
{

	var DivUpID = jQuery('.up_' + comment_id);
	var DivUpCount = Number(DivUpID.html());

	var NewCount = DivUpCount + 1;

	if (NewCount > 0)
		var color = '#3b9a22';
	else
		var color = 'red';

	if (NewCount == 0)
	{
		NewCount = '';
	}

	jQuery.ajax({
		type: "POST",
		url: "/bitrix/components/prmedia/treelike_comments/ajax_actions.php",
		data: {'VoteUp': comment_id},
		cache: false,
		success: function(otvet)
		{
			var res = otvet;
			if (res == 1)
			{
				DivUpID.html(NewCount);
				DivUpID.css('color', color);
			}
			else
			{
				alert(res);
			}
		}

	});
}

function VoteDown(comment_id)
{
	var DivDownID = jQuery('.up_' + comment_id);
	var DivDownCount = Number(DivDownID.html());

	var NewCount = DivDownCount - 1;

	if (NewCount < 0)
		var color = 'red';
	else
		var color = '#3b9a22';


	if (NewCount == 0)
	{
		NewCount = '';
	}

	jQuery.ajax({
		type: "POST",
		url: "/bitrix/components/prmedia/treelike_comments/ajax_actions.php",
		data: {'VoteDown': comment_id},
		cache: false,
		success: function(otvet)
		{
			var res = otvet;
			if (res == 1)
			{
				DivDownID.html(NewCount);
				DivDownID.css('color', color);
			}
			else
			{
				alert(res);
			}
		}

	});
}

jQuery().ready(function()
{

	jQuery('#checkRobotLabel').bind('click', function() {

		if (jQuery("#checkRobotInput").is(':checked')) {
			jQuery("#checkRobotInput").removeAttr("checked");
			jQuery('#robotString').val(generatedString);
		}
		else {
			jQuery("#checkRobotInput").attr("checked", "checked");
			jQuery('#robotString').val('');
		}
	})

	jQuery("#checkRobotInput").change(function() {
		if (!jQuery(this).is(':checked'))
			jQuery('#robotString').val(generatedString);
		else
			jQuery('#robotString').val('');
	});

	jQuery('#quoteIcon').click(function() {
		if (last_active_element == "comment")
		{
			insertAtCursor("[quote]" + getSelectedText() + "[/quote]");
			last_active_element = "";
		}
		else
		{
			encloseSelection('[quote]', '[/quote]');
			last_active_element = "";
		}
	});

	jQuery('.comment_item_content').mouseup(function() {
		last_active_element = "comment";
	});

	jQuery('#TEXT').mouseup(function() {
		last_active_element = "textarea";
	});
	jQuery('#TEXT').select(function() {
		window.prmedia_comment_selected_text = document.getSelection().toString();
	});
	jQuery('#TEXT').keydown(function () {
		window.prmedia_comment_selected_text = '';
	});


	jQuery('#link').click(function() {
		var link = prompt(TYPE_LINK);
		if (link)
		{
			var link_text = '';
			if (window.prmedia_comment_selected_text) {
				link_text = link_text = prompt(TYPE_LINK_TEXT, window.prmedia_comment_selected_text);
			} else {
				link_text = prompt(TYPE_LINK_TEXT);
			}
			if (!link_text)
				link_text = link;

			link = str_replace('http://', '', link);
			var full_link = "[url=http://" + link + "]" + link_text + "[/url]";

			insertAtCursor(full_link);
		}

	});

	jQuery('#image').click(function() {
		var image = prompt(TYPE_IMAGE);

		if (image) {
			var full_image = "[IMG]" + image + "[/IMG]";
			insertAtCursor(full_image);
		}
	});

	if (save_id > 0)
	{
		SaveData(save_id);
	}

	else if (save_id != -1)
	{
		SaveData(0);
	}

	if (scroll != 0)
	{
		scrollToComment(scroll, to_moderate);
		scroll = 0;
	}

	jQuery('div[id^=comment_]').hover(function()
	{
		var id = jQuery(this).attr('id');
		jQuery('a#' + id).css('font-weight', 'bold');
	}, function() {

		var id = jQuery(this).attr('id');
		jQuery('a#' + id).css('font-weight', 'normal');

	});

	jQuery('#form_show').click(function()
	{
		jQuery('#addform').fadeIn(1000);
	});

	jQuery('#new_comment_form').submit(function() {

		if (jQuery('#checkRobotInput').is(':checked'))
		{
			alert(ROBOT_ERROR);
			return false;
		}

		else if (jQuery('#EMAIL').val() != '')
		{
			var email = jQuery('#EMAIL').val();
			var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			if (!email.match(reg))
			{
				alert(INVALID_EMAIL);
				return false;
			}
		}

		else if (jQuery('#AUTHOR_NAME').val() == '')
		{
			alert(PLEASE_ENTER_NAME);
			return false;
		}

		else if (jQuery('#TEXT').val() == '')
		{
			alert(PLEASE_ENTER_COMMENT);
			return false;
		}

		else
		{
			jQuery(this).find("button[type=submit]").attr('disabled', 'disabled');
			jQuery(this).find("button[type=submit]").css('cursor', 'wait');
			return true;
		}

	});
});

(function($) {
	$().ready(function() {
		var new_robot_string_input_$ = $('#newRobotString'),
			comment_text_$ = $('#TEXT'),
			comment_text = comment_text_$.text(),
			comment_value = comment_text_$.val();
		if (new_robot_string_input_$.length > 0) {
			comment_text_$.keypress(function() {
				new_robot_string_input_$.val('success');
			});
		}
		if (comment_text.length > 0 && comment_value.length === 0) {
			comment_text_$.val(comment_text);
		}
	});
})(jQuery);