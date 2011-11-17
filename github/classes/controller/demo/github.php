<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Demo_Github extends Controller_Demo_OAuth2 {

	public function demo_user()
	{
		if ($this->request->method() === 'POST')
		{
			// Get the username from POST
			$user = Arr::get($_POST, 'user');

			$api = Github::factory('user');

			list($meta, $data) = $api->user($this->token, array('user' => $user));

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

	public function demo_user_followers()
	{
		if (isset($_GET['user']))
		{
			$api = Github::factory('user');

			list($meta, $data) = $api->followers($this->token, $_GET);

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

			list($meta, $data) = $api->following($this->token, array('user' => $user));

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

			list($meta, $data) = $api->follow($this->token, array('user' => $user));

			$this->content = Debug::vars($meta, $data);
		}
		else
		{
			$this->content = View::factory('demo/form')
				->set('message', 'Enter the username to follow.')
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

			list($meta, $data) = $api->unfollow($this->token, array('user' => $user));

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

}
