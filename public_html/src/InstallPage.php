<?php
// Copyright © 2017 Alexey Vaskovsky.
//
// This file is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 3.0 of the License, or (at your option) any later version.
//
// This file is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
// Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public
// License along with this file. If not, see
// <http://www.gnu.org/licenses/>
namespace AVaskovsky\WebApplicationExample;
/**
 * Installs/updates the database.
 *
 * @author Alexey Vaskovsky
 */
class InstallPage extends ApplicationPage
{
	/**
	 * The GET action.
	 *
	 * @return a string; never null.
	 */
	public function doGet()
	{
		//
		header("Content-Type: text/plain;charset=UTF-8");
		try {
			$msg_installing = _("Installing version");
			$pdo = $this->getPDO();
			$app_name = $pdo->quote("web-application-example");
			$get_version_sql = "select * from install where name = $app_name";
			try {
				$install = $pdo->query($get_version_sql)->fetchObject();
			} catch (\PDOException $ex) {
				$install = null;
			}

			// Installation

			if (is_null($install)) {
				echo "* $msg_installing 1.0\n";
				flush();
				$sql = "create table install";
				$sql .= "(name text not null primary key,";
				$sql .= "major_version integer not null,";
				$sql .= "minor_version integer not null)";
				$pdo->exec($sql);
				$sql = "insert into install(name,major_version,minor_version)";
				$sql .= "values($app_name, 1, 0)";
				$pdo->exec($sql);
				$install = $pdo->query($get_version_sql)->fetchObject();
			}

			// Version 1.x

			if ($install->major_version == 1) {

				// Version 1.0

				if ($install->minor_version == 0) {
					echo "* $msg_installing 1.1\n";
					flush();
					$sql = "create table account";
					$sql .= "(id serial not null primary key,";
					$sql .= "username text not null unique,";
					$sql .= "password text not null,";
					$sql .= "role_admin bool not null)";
					$pdo->exec($sql);
					$hash = $this->getAuthentication()->getPasswordHash("admin");
					$hash = $pdo->quote($hash);
					$sql = "insert into account(username,password,role_admin)";
					$sql .= "values('admin',$hash,true)";
					$pdo->exec($sql);
					$sql = "update install set minor_version = 1 ";
					$sql .= "where name = $app_name and major_version = 1";
					$pdo->exec($sql);
					$install = $pdo->query($get_version_sql)->fetchObject();
				}

				// Version 1.1

				if ($install->minor_version == 1) {
					echo "* $msg_installing 3.0\n";
					flush();
					$sql = "alter table account ";
					$sql .= "rename column role_admin to is_account_manager";
					$pdo->exec($sql);
					$sql = "update install set major_version = 3,";
					$sql .= "minor_version = 0 ";
					$sql .= "where name = $app_name and major_version = 1";
					$pdo->exec($sql);
					$install = $pdo->query($get_version_sql)->fetchObject();
				}

				// Unknown version

				else {
					$errmsg = _("Incompatible database version");
					$errmsg .= ": {$install->major_version}\n";
					die($errmsg);
				}
			}

			// Version 3.x

			if ($install->major_version == 3) {
				// no updates
			}


			// Unknown version

			else {
				$errmsg = _("Incompatible database version");
				$errmsg .= ": {$install->major_version}\n";
				die($errmsg);
			}

			$msg = _("OK");
			$msg .= ". ";
			$msg .= _("Current version");
			$msg .= ": {$install->major_version}.{$install->minor_version}\n";
			echo $msg;
		} catch (\Exception $ex) {
			die("ERROR: $ex\n");
		}
	}
}
