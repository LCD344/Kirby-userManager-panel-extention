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
				<td>Avatar</td>
				<td>Username</td>
				<td>Email</td>
				<td>Role</td>
				<?php if ($admin || $user->isCurrent()) { ?>
					<td></td>
					<td></td>
				<?php } ?>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($users as $user) { ?>
				<tr>
					<td>
						<a class="item-image-container" href="<?php __($user->url('edit')) ?>">
							<img src="<?php __($user->avatar(50)->url()) ?>" alt="<?php __($user->username()) ?>">
						</a>
					</td>
					<td>
						<a href="<?php __($user->url('edit')) ?>">
							<strong class="item-title"><?php __($user->username()) ?></strong>
						</a>
					</td>
					<td>
						<?php __($user->email()) ?>
					</td>
					<td>
						<?php __($user->role()->name()) ?>
					</td>
					<?php if ($admin || $user->isCurrent()) { ?>
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
				<td>Avatar</td>
				<td>Username</td>
				<td>Email</td>
				<td>Role</td>
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
	var userManagement = $('#userManagement').DataTable({
		responsive: true,
		autoWidth: false
	});
	$(document).ready(function(){
		$(window).resize(function(){

		});
	});
</script>