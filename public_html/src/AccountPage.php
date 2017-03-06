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
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#create()
	 *
	 * @implements AVaskovsky::WebApplication::CatalogPage
	 */
	protected function create()
	{
		//
		return [
			"id" => "",
			"username" => "",
			"password" => "",
			"is_account_manager" => false
		];
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#sanitize()
	 *
	 * @implements AVaskovsky::WebApplication::CatalogPage
	 */
	protected function sanitize($x)
	{
		// x: object -null @CatalogPage
		return [
			"id" => (int) trim(@$x->id),
			"username" => trim(@$x->username),
			"password" => trim(@$x->password),
			"is_account_manager" => (bool) trim(@$x->is_account_manager)
		];
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#validateUpdate()
	 *
	 * @implements AVaskovsky::WebApplication::CatalogPage
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
	 * @implements AVaskovsky::WebApplication::CatalogPage
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
	 * @implements AVaskovsky::WebApplication::CatalogPage
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
	 * @implements AVaskovsky::WebApplication::CatalogPage
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
	 * @implements AVaskovsky::WebApplication::CatalogPage
	 */
	protected function insert($x)
	{
		// x: valid model -null @CatalogPage
		$hash = $this->getAuthentication()->getPasswordHash($x->password);
		$pdo = $this->getPDO();
		$sql = "insert into account (";
		$sql .= "username, password, is_account_manager";
		$sql .= ") values (";
		$sql .= $pdo->quote($x->username) . ", ";
		$sql .= $pdo->quote($hash) . ", ";
		$sql .= ($x->is_account_manager ? "true" : "false") . ")";
		return $pdo->exec($sql);
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#update()
	 *
	 * @implements AVaskovsky::WebApplication::CatalogPage
	 */
	protected function update($x)
	{
		// x: valid model -null @CatalogPage
		$pdo = $this->getPDO();
		$sql = "update account set ";
		if(empty($x->password)) {
			$sql .= "username = " . $pdo->quote($x->username) . ", ";
			$sql .= "is_account_manager = ";
			$sql .= ($x->is_account_manager ? "true" : "false") . " ";
		} else {
			$hash = $this->getAuthentication()->getPasswordHash($x->password);
			$sql .= "password = " . $pdo->quote($hash);
		}
		$sql .= "where id = " . (int) $x->id;
		return $pdo->exec($sql);
	}
	/**
	 * @copydoc AVaskovsky::WebApplication::CatalogPage#delete()
	 *
	 * @implements AVaskovsky::WebApplication::CatalogPage
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
