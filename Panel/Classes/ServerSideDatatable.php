<?php
/**
 * Created by PhpStorm.
 * User: lcd34
 * Date: 22/1/2017
 * Time: 3:29 PM
 */

namespace lcd344\Panel;


class ServerSideDatatable {

	public static function search(\lcd344\Panel\Collections\Users $users, $request, $columns) {

		$data = $users;
		if ($request['search']['value'] != '') {
			$data = self::filterData($data, $request['search']['value'], $columns);
		}

		$filteredData = $data->slice($request['start'], $request['length']);

		return array(
			"draw" => isset ($request['draw']) ?
				intval($request['draw']) :
				0,
			"recordsTotal" => $users->count(),
			"recordsFiltered" => $data->count(),
			"data" => self::data_output($columns, $filteredData)
		);
	}

	/**
	 * @param Collections\Users $users
	 * @param $request
	 * @param $columns
	 *
	 * @return \Collection
	 */
	protected static function filterData(\lcd344\Panel\Collections\Users $users, $request, $columns) {
		/** @noinspection PhpParamsInspection */
		$data = $users->filter(function ($user) use ($columns, $request) {
			foreach ($columns as $column) {
				if ($column != 'Avatar') {
					if (is_array($column)) {
						$content = $user->{$column['name']};
					} else {
						$content = $user->$column;
					}
					if (strpos($content, $request) !== false) {
						return true;
					}
				}
			}

			return false;
		});

		return $data;
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
}