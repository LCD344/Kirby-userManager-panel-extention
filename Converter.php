<?php
/**
 * Created by PhpStorm.
 * User: lcd34
 * Date: 30/8/2017
 * Time: 3:33 PM
 */

namespace lcd344\UserManager;


use c;
use lcd344\UserModel;
use lcd344\Users;

class Converter {

	static function toDatabase($folder){
		$files = \dir::read($folder);
		$i = 1;
		foreach ($files as $file){
			$content = \yaml::read($folder . DS . $file);
			$user = new UserModel;
			$user->setCasts(c::get('userManager.database.structures',[]));
			$columns = $user->getConnection()->getSchemaBuilder()->getColumnListing($user->getTable());
			foreach ($content as $key => $entry){
				if(in_array($key, $columns, true)){
					$user->$key = $entry;
				}
			}
			$user->save();
			echo $i++ . " converted" .PHP_EOL;
		}
	}

	static function toFiles(){
		require_once  __DIR__ . '/vendor/autoload.php';
		require_once __DIR__ . '/Classes/Capsule.php';
		require_once __DIR__ . '/Traits/EDBUser.php';
		require_once __DIR__ . '/Classes/UserModel.php';

		$users = UserModel::all();
		$userCollection = new Users();
		$i = 0;
		foreach ($users as $user){
			$user->setCasts(c::get('userManager.database.structures',[]));
			$data = [];
			foreach ($user->getAttributes() as $key => $entry){
				$data[$key] = $user->$key;
			}
			$userCollection->create($data);
			echo $i++ . " converted" .PHP_EOL;
		}
	}
}