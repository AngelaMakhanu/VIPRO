jQuery(document).ready(function(e){var t=e("#tutor-instructor-signature-upload"),r=e("#tutor_pro_custom_signature_file_uploader"),i=e("#tutor_pro_custom_signature_file_deleter"),n=t.find('[type="file"]'),u=t.find("img");r.click(function(t){t.preventDefault(),n.trigger("click")}),n.change(function(t){var e=t.target.files;e&&0!=e.length&&((t=new FileReader).onload=function(t){u.attr("src",t.target.result)},t.readAsDataURL(e[0]),i.show())}),i.click(function(t){t.preventDefault();t=e(this).parent();t.find("input").val(""),t.find("img").removeAttr("src"),i.hide()})});