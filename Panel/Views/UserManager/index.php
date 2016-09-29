<?= css('//cdn.datatables.net/1.10.12/css/jquery.dataTables.css') ?>
<?= css('//cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.css') ?>

<?= js('//cdn.datatables.net/1.10.12/js/jquery.dataTables.js') ?>
<?= js('//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js') ?>
<div class="section">

	<h2 class="hgroup hgroup-single-line cf">
    <span class="hgroup-title">
		<?php _l('users.index.headline') ?>
		<span class="counter">( <?= $users->count() ?> )</span>
    </span>
		<?php if ($admin) { ?>
			<span class="hgroup-options shiv shiv-dark shiv-left">
			  <a title="+" data-shortcut="+" class="hgroup-option-right" href="<?php _u('userManagement/add') ?>">
				<?php i('plus-circle', 'left') . _l('users.index.add') ?>
			  </a>
			</span>
		<?php } ?>
	</h2>

	<div>
		<table id="userManagement">
			<thead>
			<tr>
				<?php foreach ($fields as $field => $val) { ?>
					<td><?= $field ?></td>
				<?php } ?>
				<?php if ($admin || $user->isCurrent()) { ?>
					<td></td>
					<td></td>
				<?php } ?>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($users as $user) { ?>
				<tr>
					<?php foreach ($fields as $field) { ?>
						<td>
							<?php if (is_array($field)) {
								if (isset($field['action']) && $field['action'] == 'edit' || $field['action'] == 'email') {
									?>
									<a href="<?php __($user->url($field['action'])) ?>">
								<?php } ?>
								<?= (isset($field['element']) ? "<{$field['element']} " : "") ?><?= (isset($field['class']) ? "class=\"{$field['class']}\" " : "") ?><?= (isset($field['element']) ? ">" : "") ?><?php  __($user->{$field['name']}()) ?><?= (isset($field['element']) ? "</{$field['element']}>" : "") ?>
								<?php if (isset($field['action']) && $field['action'] == 'edit' || $field['action'] == 'email') { ?>
									</a>
								<?php }
							} else if ($field == "Avatar") { ?>
								<a class="item-image-container" href="<?php __($user->url('edit')) ?>">
									<img src="<?php __($user->avatar(50)->url()) ?>"
										 alt="<?php __($user->username()) ?>">
								</a>
							<?php } else { ?>
								<?php __($user->$field()) ?>
							<?php } ?>
						</td>
						<?php
					}
						if ($admin || $user->isCurrent()) { ?>
							<td>
								<a class="btn btn-with-icon" href="<?php __($user->url('edit')) ?>">
									<?php i('pencil', 'left') . _l('users.index.edit') ?>
								</a>
							</td>
							<td>
								<a data-modal class="btn btn-with-icon" href="<?php __($user->url('delete')) ?>">
									<?php i('trash-o', 'left') . _l('users.index.delete') ?>
								</a>
							</td>
						<?php } ?>
				</tr>
			<?php } ?>
			</tbody>
			<tfoot>
			<tr>
				<?php foreach ($fields as $field => $val) { ?>
					<td><?= $field ?></td>
				<?php } ?>
				<?php if ($admin || $user->isCurrent()) { ?>
					<td></td>
					<td></td>
				<?php } ?>
			</tr>
			</tfoot>
		</table>
	</div>
</div>

<script>
	$('#userManagement').on('init page.dt processing.dt order.dt draw.dt', function () {
		$(".paginate_button").attr("href", "#");
		$(".paginate_button").bind('click', function (e) {
			e.preventDefault();
		});
	}).DataTable({
		responsive: true,
		autoWidth: false
	});
</script>