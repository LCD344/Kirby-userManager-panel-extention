<?php
/**
 * Created by PhpStorm.
 * User: lcd34
 * Date: 31/8/2017
 * Time: 12:44 AM
 */

namespace lcd344\Panel;


use lcd344\Panel\Models\User;
use lcd344\UserModel;

class ServerSideDatabaseDatatable {


	public static function search($request, $columns) {

		$query = self::getAllowedRoles($columns);
		$totalCount = $query->count();

		if ($request['search']['value'] != '') {
			$searchValue = $request['search']['value'];
			$query->where(function($query) use ($columns,$searchValue){
				foreach ($columns as $column) {
					if ($column != 'Avatar') {
						if (is_array($column)) {
							$query->orWhere(strtolower($column['name']),'like',"%{$searchValue}%");
						} else {
							$query->orWhere(strtolower($column),'like',"%{$searchValue}%");
						}
					}
				}
			});
		}

		$filteredCount = $query->count();

		$numberedColumns = array_values($columns);
		$orderColumn = $numberedColumns[$request['order'][0]['column']];

		if($orderColumn != 'Avatar'){
			if(is_array($orderColumn)){
				$query->orderBy(strtolower($orderColumn['name']),$request['order'][0]['dir']);
			} else {
				$query->orderBy(strtolower($orderColumn),$request['order'][0]['dir']);
			}
		}


		$query->take($request['length'])->skip($request['start']);

		$data = [];
		foreach ($query->get() as $dbEntry){
			$data[] = new User($dbEntry->username);
		}

		return array(
			"draw" => isset ($request['draw']) ?
				intval($request['draw']) :
				0,
			"recordsTotal" => $totalCount,
			"recordsFiltered" => $filteredCount,
			"data" => self::data_output($columns, $data)
		);
	}

	/**
	 * Create the data output array for the DataTables rows
	 *
	 * @param  array $columns Column information array
	 * @param  \Collection $data Data from the SQL get
	 *
	 * @return array Formatted data in a row based format
	 */
	static function data_output($columns, $data) {
		$out = [];
		foreach ($data as $entry) {
			$row = [];
			foreach ($columns as $column) {
				$colData = '';
				if (is_array($column)) {
					if (isset($column['action']) && $column['action'] == 'edit' || $column['action'] == 'email') {
						$colData .= '<a href="' . $entry->url($column['action']) . '">';
					}
					$colData .= (isset($column['element']) ? "<{$column['element']}" : "") . (isset($column['class']) ? "class=\"{$column['class']}\" " : "") . (isset($column['element']) ? ">" : "") . $entry->{$column['name']}() . (isset($column['element']) ? "</{$column['element']}>" : "");
					if (isset($column['action']) && $column['action'] == 'edit' || $column['action'] == 'email') {
						$colData .= '</a>';
					}
				} else if ($column == "Avatar") {
					$colData .= '<a class="item-image-container" href="' . $entry->url('edit') . '">';
					$colData .= '<img src="' . $entry->avatar(50)->url() . '" alt="' . $entry->username() . '">';
					$colData .= '</a>';
				} else if ($column == "Role") {
					$colData .= $entry->role()->name();
				} else {
					$colData .= $entry->$column();
				}

				$row[] = $colData;
			}

			if ($entry->ui()->update()) {
				$row[] = '<a class="btn btn-with-icon" href="' . $entry->url('edit') . '">' .
					icon('pencil', 'left') . htmlspecialchars(l('users.index.edit')) .
					'</a>';
			} else {
				$row[] = '';
			}

			if ($entry->ui()->delete()) {
				$row[] = '<a data-modal class="btn btn-with-icon" href="' . $entry->url('delete') . '">' .
					icon('trash-o', 'left') . htmlspecialchars(l('users.index.edit')) .
					'</a>';
			} else {
				$row[] = '';
			}

			$out[] = $row;
		}

		return $out;
	}

	/**
	 * @param $columns
	 *
	 * @return array
	 */
	public static function getAllowedRoles($columns) {

		$query = UserModel::query();

		if (isset($columns['Role'])) {
			$allowedRoles = [];
			$roles = UserModel::select('role')->groupBy('role')->get();
			foreach ($roles as $role) {
				$testUser = UserModel::where('role', $role->role)->first();
				$testUser = new User($testUser->username);
				if ($testUser->ui()->read()) {
					$allowedRoles[] = $role->role;
				}
			}

			$query->where(function($query) use ($allowedRoles){
				foreach ($allowedRoles as $role){
					$query->orWhere('role',$role);
				}
				return $query;
			});
		}

		return $query;
	}
}
