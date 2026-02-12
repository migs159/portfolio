<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Default OpenAI configuration
// To change the model for all clients, update the `openai_model` value below.
// You can also override this per-request in your API client code.

$config['openai_model'] = 'gpt-5-mini';
// Example: 'gpt-5-mini' or 'gpt-4o' etc.

return $config;
