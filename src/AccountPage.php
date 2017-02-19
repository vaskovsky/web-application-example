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
 * The account management page.
 *
 * @extends AVaskovsky::WebApplication::CatalogPage
 *
 * @author Alexey Vaskovsky
 */
class AccountPage extends ApplicationPage
{
	/**
	 * @copydoc AVaskovsky::WebApplication::AbstractPage#getRole()
	 *
	 * @see AVaskovsky::WebApplication::AbstractPage#getRole()
	 */
	protected function getRole()
	{
		//
		return "admin";
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#create()
	 *
	 * @see AVaskovsky::WebApplication::CatalogPage#create()
	 */
	protected function create()
	{
		//
		return [
			"id" => "",
			"username" => "",
			"password" => "",
			"role_admin" => false
		];
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#sanitize()
	 *
	 * @see AVaskovsky::WebApplication::CatalogPage#sanitize()
	 */
	protected function sanitize($x)
	{
		// x: object -null @CatalogPage
		return [
			"id" => (int) trim(@$x->id),
			"username" => trim(@$x->username),
			"password" => trim(@$x->password),
			"role_admin" => (bool) trim(@$x->role_admin)
		];
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#validateUpdate()
	 *
	 * @see AVaskovsky::WebApplication::CatalogPage#validateUpdate()
	 */
	protected function validateUpdate($x)
	{
		// x: sanitized model -null @CatalogPage
		if (empty($x->password)) {
 			if (empty($x->username))
				return "Empty username";
		}
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#validateInsert()
	 *
	 * @see AVaskovsky::WebApplication::CatalogPage#validateInsert()
	 */
	protected function validateInsert($x)
	{
		// x: sanitized model -null @CatalogPage
		if (empty($x->password))
			return "Empty password";
		return $this->validateUpdate($x);
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#select()
	 *
	 * @see AVaskovsky::WebApplication::CatalogPage#select()
	 */
	protected function select($x)
	{
		// x: sanitized model -null @CatalogPage
		$pdo = $this->getPDO();
		$sql = "select * from account";
		return $pdo->query($sql);
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#get()
	 *
	 * @see AVaskovsky::WebApplication::CatalogPage#get()
	 */
	protected function get($x)
	{
		// x: sanitized model -null @CatalogPage
		$pdo = $this->getPDO();
		$sql = "select * from account where ";
		$sql .= "id = " . (int) $x->id;
		return $pdo->query($sql)->fetchObject();
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#insert()
	 *
	 * @see AVaskovsky::WebApplication::CatalogPage#insert()
	 */
	protected function insert($x)
	{
		// x: valid model -null @CatalogPage
		$hash = $this->getAuthorization()->getPasswordHash($x->password);
		$pdo = $this->getPDO();
		$sql = "insert into account (";
		$sql .= "username, password, role_admin";
		$sql .= ") values (";
		$sql .= $pdo->quote($x->username) . ", ";
		$sql .= $pdo->quote($hash) . ", ";
		$sql .= ($x->role_admin ? "true" : "false") . ")";
		return $pdo->exec($sql);
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#update()
	 *
	 * @see AVaskovsky::WebApplication::CatalogPage#update()
	 */
	protected function update($x)
	{
		// x: valid model -null @CatalogPage
		$pdo = $this->getPDO();
		$sql = "update account set ";
		if(empty($x->password)) {
			$sql .= "username = " . $pdo->quote($x->username) . ", ";
			$sql .= "role_admin = " . ($x->role_admin ? "true" : "false") . " ";
		} else {
			$hash = $this->getAuthorization()->getPasswordHash($x->password);
			$sql .= "password = " . $pdo->quote($hash);
		}
		$sql .= "where id = " . (int) $x->id;
		return $pdo->exec($sql);
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#delete()
	 *
	 * @see AVaskovsky::WebApplication::CatalogPage#delete()
	 */
	protected function delete($x)
	{
		// x: sanitized model -null @CatalogPage
		$pdo = $this->getPDO();
		$sql = "delete from account where id = " . (int) $x->id;
		return $pdo->exec($sql);
	}
	use \AVaskovsky\WebApplication\CatalogPage;
}
