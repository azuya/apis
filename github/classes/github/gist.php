<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @link  http://developer.github.com/v3/gists/
 */
class Github_Gist extends Github {

	public function pub(OAuth2_Token_Access $token = NULL, array $params = NULL)
	{
		$request = OAuth2_Request::factory('resource', 'GET', $this->url('gists/public'));

		if ($token)
		{
			$params['access_token'] = $token->token;
		}

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function user(OAuth2_Token_Access $token = NULL, array $params = NULL)
	{
		if ($user = Github::remove($params, 'user'))
		{
			$url = $this->url('user/:user/gists', array(
					':user' => $user,
				));
		}
		else
		{
			$url = $this->url('gists');
		}

		$request = OAuth2_Request::factory('resource', 'GET', $url);

		if ($token)
		{
			$params['access_token'] = $token->token;
		}

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function get(OAuth2_Token_Access $token = NULL, array $params = NULL)
	{
		$url = $this->url('gists/:id', array(
				'id' => Github::remove($params, 'id'),
			));

		$request = OAuth2_Request::factory('resource', 'GET', $url);

		if ($token)
		{
			$params['access_token'] = $token->token;
		}

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function starred(OAuth2_Token_Access $token, array $params = NULL)
	{
		$request = OAuth2_Request::factory('resource', 'GET', $this->url('gists/starred'), array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		return $this->execute($request);
	}

	public function is_star(OAuth2_Token_Access $token, array $params = NULL)
	{
		$url = $this->url('gists/:id/star', array(
				'id' => Github::remove($params, 'id'),
			));

		$request = OAuth2_Request::factory('resource', 'GET', $url, array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		return $this->execute_boolean($request);
	}

	public function star(OAuth2_Token_Access $token, array $params = NULL)
	{
		$url = $this->url('gists/:id/star', array(
				'id' => Github::remove($params, 'id'),
			));

		$request = OAuth2_Request::factory('resource', 'PUT', $url, array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		return $this->execute_boolean($request);
	}

	public function unstar(OAuth2_Token_Access $token, array $params = NULL)
	{
		$url = $this->url('gists/:id/star', array(
				'id' => Github::remove($params, 'id'),
			));

		$request = OAuth2_Request::factory('resource', 'DELETE', $url, array(
				'access_token' => $token->token,
			))
			->required('access_token', TRUE)
			;

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		return $this->execute_boolean($request);
	}

}
