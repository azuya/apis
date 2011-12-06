<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Demo_Github extends Controller_Demo_OAuth2 {

	public function demo_user()
	{
		if ($this->request->method() === 'POST')
		{
			$user = Arr::get($_POST, 'user');

			$api = Github::factory('user');

			list($meta, $data) = $api->user($this->token, $user);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the username to look up, or leave blank for the authenticated user.')
				->set('inputs', array(
					'Username' => Form::input('user'),
				))
				;
		}
	}

	public function demo_user_emails()
	{
		$api = Github::factory('user');

		list($meta, $data) = $api->emails($this->token);

		$this->content = Debug::vars($meta, $data);
	}

	public function demo_user_emails_add()
	{
		if ($this->request->method() === Request::POST)
		{
			// Get the emails from POST
			$emails = preg_split('/,\s*/', Arr::get($_POST, 'emails'));

			$api = Github::factory('user');

			list($meta, $data) = $api->emails_add($this->token, $emails);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter a comma separated list of emails to add.')
				->set('inputs', array(
					'Emails' => Form::textarea('emails'),
				))
				;
		}
	}

	public function demo_user_emails_delete()
	{
		if ($this->request->method() === Request::POST)
		{
			// Get the emails from POST
			$emails = preg_split('/,\s*/', Arr::get($_POST, 'emails'));

			$api = Github::factory('user');

			list($meta, $data) = $api->emails_delete($this->token, $emails);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter a comma separated list of emails to delete.')
				->set('inputs', array(
					'Emails' => Form::textarea('emails'),
				))
				;
		}
	}

	public function demo_user_followers()
	{
		if (isset($_GET['user']))
		{
			$user = Arr::get($_GET, 'user');

			$params = Arr::extract($_GET, array('page'));

			$api = Github::factory('user');

			list($meta, $data) = $api->followers($this->token, $user, $params);

			if ( ! empty($meta['page']))
			{
				$links = array();

				foreach ($meta['page'] as $name => $page)
				{
					$page = $this->request->url().URL::query(array(
							'page' => $page,
						));

					$links[] = HTML::anchor($page, $name);
				}
			}

			$this->content = View::factory('demo/github/paged')
				->bind('links', $links)
				->set('meta', $meta)
				->set('data', $data)
				;
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('method', 'get')
				->set('message', 'Enter the username to look up, or leave blank for the authenticated user.')
				->set('inputs', array(
					'Username' => Form::input('user'),
				))
				;
		}
	}

	public function demo_user_following()
	{
		if ($this->request->method() === Request::POST)
		{
			// Get the username from POST
			$user = Arr::get($_POST, 'user');

			$api = Github::factory('user');

			list($meta, $data) = $api->following($this->token, $user);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the username to check.')
				->set('inputs', array(
					'Username' => Form::input('user'),
				))
				;
		}
	}

	public function demo_user_follow()
	{
		if ($this->request->method() === Request::POST)
		{
			// Get the username from POST
			$user = Arr::get($_POST, 'user');

			$api = Github::factory('user');

			list($meta, $data) = $api->follow($this->token, $user);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the username to follow.<br/><small>If this fails, you may need to login again with the "user" scope enabled.')
				->set('inputs', array(
					'Username' => Form::input('user'),
				))
				;
		}
	}

	public function demo_user_unfollow()
	{
		if ($this->request->method() === Request::POST)
		{
			// Get the username from POST
			$user = Arr::get($_POST, 'user');

			$api = Github::factory('user');

			list($meta, $data) = $api->unfollow($this->token, $user);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the username to unfollow.')
				->set('inputs', array(
					'Username' => Form::input('user'),
				))
				;
		}
	}

	public function demo_user_keys()
	{
		$api = Github::factory('user');

		list($meta, $data) = $api->keys($this->token);

		$this->content = Debug::vars($meta, $data);
	}

	public function demo_user_key()
	{
		if ($this->request->method() === Request::POST)
		{
			// Get the key id from POST
			$id = Arr::get($_POST, 'id');

			$api = Github::factory('user');

			list($meta, $data) = $api->key($this->token, $id);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter a key id.')
				->set('inputs', array(
					'ID' => Form::input('id'),
				))
				;
		}
	}

	public function demo_user_key_add()
	{
		if ($this->request->method() === Request::POST)
		{
			// Get the key data from POST
			$key = Arr::extract($_POST, array('title', 'key'));

			$api = Github::factory('user');

			list($meta, $data) = $api->key_add($this->token, $key);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Create a new SSH key.')
				->set('inputs', array(
					'Title' => Form::input('title'),
					'SSH Key' => Form::textarea('key'),
				))
				;
		}
	}

	public function demo_user_key_update()
	{
		if ($this->request->method() === Request::POST)
		{
			// Get the key id from POST
			$id = Arr::get($_POST, 'id');

			// Get the key data from POST
			$key = Arr::extract($_POST, array('title', 'key'));

			$api = Github::factory('user');

			list($meta, $data) = $api->key_update($this->token, $id, $key);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Update an SSH key.')
				->set('inputs', array(
					'ID' => Form::input('id'),
					'Title' => Form::input('title'),
					'SSH Key' => Form::textarea('key'),
				))
				;
		}
	}

	public function demo_user_key_delete()
	{
		if ($this->request->method() === Request::POST)
		{
			// Get the key id from POST
			$id = Arr::get($_POST, 'id');

			$api = Github::factory('user');

			list($meta, $data) = $api->key_delete($this->token, $id);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter a key id.')
				->set('inputs', array(
					'Key ID' => Form::input('id'),
				))
				;
		}
	}

	public function demo_gists()
	{
		if ($this->request->method() === 'POST')
		{
			$params = Arr::extract($_POST, array('user'));

			$api = Github::factory('gist');

			list($meta, $data) = $api->user($this->token, $params);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the username to look up, or leave blank for the authenticated user.')
				->set('inputs', array(
					'Username' => Form::input('user'),
				))
				;
		}
	}

	public function demo_gists_public()
	{
		$api = Github::factory('gist');

		list($meta, $data) = $api->pub($this->token);

		$this->content = Debug::vars($meta, $data);
	}

	public function demo_gists_starred()
	{
		$api = Github::factory('gist');

		list($meta, $data) = $api->starred($this->token);

		$this->content = Debug::vars($meta, $data);
	}

	public function demo_gists_get()
	{
		if ($this->request->method() === 'POST')
		{
			$params = Arr::extract($_POST, array('id'));

			$api = Github::factory('gist');

			list($meta, $data) = $api->get($this->token, $params);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the Gist ID to look up.')
				->set('inputs', array(
					'Identifier' => Form::input('id'),
				))
				;
		}
	}

	public function demo_gists_is_star()
	{
		if ($this->request->method() === 'POST')
		{
			$params = Arr::extract($_POST, array('id'));

			$api = Github::factory('gist');

			list($meta, $data) = $api->is_star($this->token, $params);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the Gist ID to look up.')
				->set('inputs', array(
					'Identifier' => Form::input('id'),
				))
				;
		}
	}

	public function demo_gists_star()
	{
		if ($this->request->method() === 'POST')
		{
			$params = Arr::extract($_POST, array('id'));

			$api = Github::factory('gist');

			list($meta, $data) = $api->star($this->token, $params);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the Gist ID to star.')
				->set('inputs', array(
					'Identifier' => Form::input('id'),
				))
				;
		}
	}

	public function demo_gists_unstar()
	{
		if ($this->request->method() === 'POST')
		{
			$params = Arr::extract($_POST, array('id'));

			$api = Github::factory('gist');

			list($meta, $data) = $api->unstar($this->token, $params);

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the Gist ID to unstar.')
				->set('inputs', array(
					'Identifier' => Form::input('id'),
				))
				;
		}
	}

	public function demo_login()
	{
		// Attempt to complete signin
		if ($code = Arr::get($_REQUEST, 'code'))
		{
			// Exchange the authorization code for an access token
			$token = $this->provider->access_token($this->client, $code);

			// Store the access token
			$this->session->set($this->key('access'), $token);

			// Refresh the page to prevent errors
			$this->request->redirect($this->request->uri());
		}

		if ($this->request->method() === 'POST')
		{
			// We will need a callback URL for the user to return to
			$callback = $this->request->url(TRUE);

			// Add the callback URL to the consumer
			$this->client->callback($callback);

			$params = Arr::extract($_POST, array('scope'));

			if ($this->token)
			{
				// Use the existing access token
				$params['access_token'] = $this->token->token;
			}

			if (isset($params['scope']) AND is_array($params['scope']))
			{
				// Convert scopes into a comma separated list
				$params['scope'] = implode(',', $params['scope']);
			}

			// Get the login URL from the provider
			$url = $this->provider->authorize_url($this->client, $params);

			// Redirect to the twitter login page
			$this->request->redirect($url);
		}

		$scopes = array(
				'user' => 'user: read/write access to profile',
				'public_repo' => 'public_repo: read/write access to public repos',
				'repo' => 'repo: read/write access to public and private repos',
				'gist' => 'gist: write access to gists',
			);

		foreach ($scopes as $name => $description)
		{
			$scopes[$name] = Form::label(NULL, Form::checkbox('scope[]', $name).' '.$description);
		}

		if ($this->token)
		{
			$message = 'You are already logged in. Use this form to change your current scopes.';
			$footer  = Debug::vars($this->token);
		}
		else
		{
			$message = 'Login to Github.<br/>Scopes will enable additional functionality.';
		}

		$this->content = View::factory('demo/form')
			->bind('message', $message)
			->bind('footer', $footer)
			->set('inputs', array(
				'Scopes' => implode('<br/>', $scopes),
			))
			;
	}

}
