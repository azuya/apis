<?php defined('SYSPATH') or die('No direct script access.');

class Github_User extends Github {

	public function user(OAuth2_Token_Access $token = NULL, $user = NULL, array $params = NULL)
	{
		$url = $this->url(':noun/:user', array(
				'noun' => $user ? 'users' : 'user',
				'user' => $user,
			));

		$request = OAuth2_Request::factory('resource', 'GET', $url)
			->required('access_token', empty($user))
			;

		if ($token)
		{
			$params['access_token'] = $token->token;
		}

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function emails(OAuth2_Token_Access $token, array $params = NULL)
	{
		$request = OAuth2_Request::factory('resource', 'GET', $this->url('user/emails'), array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function emails_add(OAuth2_Token_Access $token, $emails, array $params = NULL)
	{
		$request = OAuth2_Request::factory('resource', 'POST', $this->url('user/emails'), array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			->body(json_encode($emails))
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function emails_delete(OAuth2_Token_Access $token, $emails, array $params = NULL)
	{
		$request = OAuth2_Request::factory('resource', 'DELETE', $this->url('user/emails'), array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			->body(json_encode($emails))
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute_boolean($request);
	}

	public function followers(OAuth2_Token_Access $token = NULL, $user = NULL, array $params = NULL)
	{
		$url = $this->url(':noun/:user/followers', array(
				'noun' => $user ? 'users' : 'user',
				'user' => $user,
			));

		$request = OAuth2_Request::factory('resource', 'GET', $url)
			->required('access_token', empty($user))
			;

		if ($token)
		{
			$params['access_token'] = $token->token;
		}

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function following(OAuth2_Token_Access $token, $user, array $params = NULL)
	{
		$url = $this->url('user/following/:user', array(
				'user' => $user,
			));

		$request = OAuth2_Request::factory('resource', 'GET', $url, array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute_boolean($request);
	}

	public function follow(OAuth2_Token_Access $token, $user, array $params = NULL)
	{
		$url = $this->url('user/following/:user', array(
				'user' => $user,
			));

		$request = OAuth2_Request::factory('resource', 'PUT', $url, array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute_boolean($request);
	}

	public function unfollow(OAuth2_Token_Access $token, array $params = NULL)
	{
		$user = Arr::get($params, 'user');

		$url = $this->url('user/following/:user', array(
				'user' => $user,
			));

		unset($params['user']);

		$request = OAuth2_Request::factory('resource', 'DELETE', $url, array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute_boolean($request);
	}

	public function keys(OAuth2_Token_Access $token, array $params = NULL)
	{
		$request = OAuth2_Request::factory('resource', 'GET', $this->url('user/keys'), array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function key(OAuth2_Token_Access $token, $id, array $params = NULL)
	{
		$url = $this->url('user/keys/:id', array(
				'id' => $id,
			));

		$request = OAuth2_Request::factory('resource', 'GET', $url, array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function key_add(OAuth2_Token_Access $token, $key, array $params = NULL)
	{
		$request = OAuth2_Request::factory('resource', 'POST', $this->url('user/keys'), array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			->body(json_encode($key))
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function key_update(OAuth2_Token_Access $token, $id, $key, array $params = NULL)
	{
		$url = $this->url('user/keys/:id', array(
				'id' => $id,
			));

		$request = OAuth2_Request::factory('resource', 'PATCH', $url, array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			->body(json_encode($key))
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function key_delete(OAuth2_Token_Access $token, $id, array $params = NULL)
	{
		$url = $this->url('user/keys/:id', array(
				'id' => $id,
			));

		$request = OAuth2_Request::factory('resource', 'DELETE', $url, array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			$request->params($params);
		}

		return $this->execute_boolean($request);
	}

}
