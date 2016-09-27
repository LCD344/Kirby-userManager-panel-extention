<?= css('https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css') ?>
<?= js('https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js') ?>
<?= css('//cdn.datatables.net/1.10.12/css/jquery.dataTables.css') ?>
<?= css('//cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.css') ?>

<?= js('//cdn.datatables.net/1.10.12/js/jquery.dataTables.js') ?>
<?= js('//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js') ?>
<div class="bars bars-with-sidebar-left cf">

	<div class="sidebar sidebar-left">
		<a class="sidebar-toggle" href="#sidebar"
		   data-hide="<?php _l('options.hide') ?>"><span><?php _l('options.show') ?></span></a>

		<div class="sidebar-content section">
			<table id="userManagement">
				<thead>
				<tr>
					<td>Username</td>
					<td>Email</td>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($users as $user) { ?>
					<tr>
						<td>
							<a href="<?= purl("userMailer/{$user->username()}") ?>">
								<strong class="item-title"><?php __($user->username()) ?></strong>
							</a>
						</td>
						<td>
							<a href="<?= purl("userMailer/{$user->username()}") ?>">
								<?php __($user->email()) ?>
							</a>
						</td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
				<tr>
					<td>Username</td>
					<td>Email</td>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>

	<div class="mainbar">
		<div class="section">
			<?= $form ?>
		</div>
	</div>

</div>

<script>
	//TODO make sent message works when there are attachments
	var userManagement = $('#userManagement').DataTable({
		responsive: true,
		autoWidth: false
	});
	userManagement.on('page.dt processing.dt order.dt',function(){
		$(".paginate_button").attr("href","#");
	});
	$(document).ready(function(){
		$(".paginate_button").attr("href","#");
	});

	$("input[type='text'], textarea").css('cursor', "text");
	$("select").css('cursor', "pointer");

	Dropzone.options.dropzoneForm = {
		url: window.location.href,

		addRemoveLinks: true,
		autoProcessQueue: false,
		uploadMultiple: true,
		parallelUploads: 100,
		maxFiles: 100,

		init: function () {
			dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

			// for Dropzone to process the queue (instead of default form behavior):
			$("input.btn.btn-rounded.btn-submit").click(function (e) {

				// Make sure that the form isn't actually being sent.
				if (dzClosure.getQueuedFiles().length > 0 && $('#form-field-subject').val() != '' && $('#form-field-body').val() != '') {
					e.preventDefault();
					e.stopPropagation();
					dzClosure.processQueue();
				}
			});


			this.on("successmultiple", function (files, response) {
				dzClosure.removeAllFiles(true);
			});
		}
	}

	function toggleCC() {
		if ($('#form-field-driver').val() == "phpmailer") {
			$('.field-name-cc').show();
			$('.field-name-bcc').show();
		} else {
			$('.field-name-cc').hide();
			$('.field-name-bcc').hide();
		}
	}

	toggleCC();
	$('#form-field-driver').on('change', toggleCC);


</script>