<?php defined('SYSPATH') or die('No direct script access.');

abstract class Github extends OAuth2_Provider_Github {

	/**
	 * @var  string  api version
	 */
	const VERSION = '3';

	/**
	 * @var  string  api url
	 */
	const URL = 'https://api.github.com';

	/**
	 * Class loader.
	 *
	 * @param  string  sub-class name
	 * @param  array   initialization options
	 */
	public static function factory($name, array $options = NULL)
	{
		$class = 'Github_'.$name;

		return new $class($options);
	}

	/**
	 * @var  array  current request headers
	 */
	protected $_headers = array();

	/**
	 * Generate an API URL. Optionally replaces :id parameters in the link.
	 *
	 * @param   string   resource link
	 * @param   array    link parameters
	 * @return  string
	 */
	public function url($link = '', array $params = NULL)
	{
		if ($link AND $params)
		{
			$replace = array();

			foreach ($params as $key => $value)
			{
				// Convert the key into an :id
				$replace[':'.$key] = $value;
			}

			// Replace :ids with values
			$link = strtr($link, $replace);
		}

		// Clean up the link
		$link = preg_replace('#//+#', '/', trim($link, '/'));

		return Github::URL."/{$link}";
	}

	/**
	 * Executes an OAuth2 request, reads headers, and decodes the response JSON.
	 *
	 * @param   object  request to be executed
	 * @param   array   additional request options
	 * @return  array   meta, data
	 */
	public function execute(OAuth2_Request $request, array $options = NULL)
	{
		$options[CURLOPT_HEADERFUNCTION] = array($this, '_read_headers');

		$this->_headers = array();

		$meta = array();
		$data = NULL;

		try
		{
			if ($data = $request->execute($options))
			{
				// Parse the JSON response
				$data = json_decode($data);
			}
		}
		catch (Kohana_OAuth_Exception $e)
		{
			if ($code = $e->getCode())
			{
				$this->_headers['status'] = $code;
			}
			else
			{
				throw $e;
			}
		}

		if ($this->_headers)
		{
			// Parse the headers into meta data
			$meta = $this->_parse_headers($this->_headers, $meta);
		}

		return array($meta, $data);
	}

	/**
	 * Executes the request, but only returns TRUE or FALSE for data.
	 *
	 * @param   object  request to be executed
	 * @param   array   additional request options
	 * @return  array   meta, data
	 */
	public function execute_boolean(OAuth2_Request $request, array $options = NULL)
	{
		list($meta) = $this->execute($request, $options);

		return array($meta, ($meta['status'] >= 200 AND $meta['status'] < 300));
	}

	/**
	 * CURL callback for reading headers.
	 *
	 * @param   resource  curl
	 * @param   string    header line
	 * @return  integer
	 */
	public function _read_headers($curl, $header)
	{
		if (preg_match('/^HTTP\/1/', $header))
		{
			$this->_headers['status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		}
		elseif (preg_match('/^X-OAuth-Scopes:\s*(.+?)?$/', $header, $matches))
		{
			$this->_headers['scopes_enabled'] = isset($matches[1]) ? trim($matches[1]) : NULL;
		}
		elseif (preg_match('/^X-Accepted-OAuth-Scopes:\s*(.+?)?$/', $header, $matches))
		{
			$this->_headers['scopes_available'] = trim($matches[1]);
		}
		elseif (preg_match('/^X-RateLimit-Limit:\s+(\d+)/i', $header, $matches))
		{
			$this->_headers['rate_max'] = (int) trim($matches[1]);
		}
		elseif (preg_match('/^X-RateLimit-Remaining:\s+(\d+)/i', $header, $matches))
		{
			$this->_headers['rate_limit'] = (int) trim($matches[1]);
		}
		elseif (preg_match('/^Location:\s+(.+?)$/i', $header, $matches))
		{
			$this->_headers['location'] = trim($matches[1]);
		}
		elseif (preg_match('/^(Link:\s+)/i', $header, $matches))
		{
			$links = trim(substr($header, strlen($matches[1])));

			/**
			 * @todo This is terrible parsing and could be broken easily.
			 *       http://www.w3.org/Protocols/9707-link-header.html
			 */
			$links = explode(', ', $links);

			foreach ($links as $link)
			{
				// Separate the link into sections
				$parts = explode('; ', $link);

				// Extract the link from the parameters
				$link = substr(trim(array_shift($parts)), 1, -1);

				foreach ($parts as $part)
				{
					preg_match('/^(.+?)="(.+?)"$/', trim($part), $matches);

					list($junk, $attr, $value) = $matches;

					$this->_headers['links'][$link][$attr] = $value;
				}
			}
		}

		return strlen($header);
	}

	public function _parse_headers(array $headers, array $meta = NULL)
	{
		if ( ! is_array($meta))
		{
			$meta = array();
		}

		if ( ! empty($headers['status']))
		{
			// HTTP status
			$meta['status'] = $headers['status'];
		}

		if ( ! empty($headers['scopes_available']))
		{
			if ($enabled = Arr::get($headers, 'scopes_enabled'))
			{
				$enabled = preg_split('/,\s*/', $enabled);
			}
			else
			{
				$enabled = array();
			}

			$available = preg_split('/,\s*/', $headers['scopes_available']);

			foreach ($available as $name)
			{
				$meta['scopes'][$name] = in_array($name, $enabled);
			}
		}

		if ( ! empty($headers['rate_limit']))
		{
			// Parse the rate limit data into a tuple
			$meta['rate'] = array($headers['rate_limit'], $headers['rate_max']);
		}

		if ( ! empty($headers['location']))
		{
			// Created resource URL
			$meta['url'] = $headers['location'];
		}

		if ( ! empty($headers['links']))
		{
			foreach ($headers['links'] as $url => $attrs)
			{
				if ($rel = Arr::get($attrs, 'rel') AND in_array($rel, array('first', 'prev', 'next', 'last')))
				{
					// Parse the query string portion of the URL
					parse_str(parse_url($url, PHP_URL_QUERY), $query);

					if ($page = Arr::get($query, 'page'))
					{
						// Add the page number for this relation
						$meta['page'][$rel] = $page;
					}
				}
			}
		}

		return $meta;
	}

}
