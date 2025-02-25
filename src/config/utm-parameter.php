<?php

return [
    /*
   * Control Overwriting UTM Parameters (default: false)
   *
   * This setting determines how UTM parameters are handled within a user's session.
   *
   * - Enabled (true): New UTM parameters will overwrite existing ones during the session.
   * - Disabled (false): The initial UTM parameters will persist throughout the session.
   */
    'override_utm_parameters' => false,

    /*
   * Session Key for UTM Parameters (default: 'utm')
   *
   * This key specifies the name used to access and store UTM parameters within the session data.
   *
   * If you're already using 'utm' for another purpose in your application,
   * you can customize this key to avoid conflicts.
   * Simply provide your preferred key name as a string value.
   */
    'session_key' => 'utm',

    /*
    * Allowed UTM Parameters (default: ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'utm_campaign_id'])
    *
    * This setting defines the UTM parameters that are allowed within your application.
    *
    * In this array, you can specify a list of allowed UTM parameter names. Each parameter should be listed as a string.
    * Only parameters from this list will be stored and processed in the session.
    * and any parameter without the 'utm_' prefix will be ignored regardless of its inclusion in this list.
    *
    * Example: To only allow the basic UTM parameters (source, medium, and campaign), you could update the array like this:
    *
    * 'allowed_utm_parameters' => [
    *     'utm_source',
    *     'utm_medium',
    *     'utm_campaign',
    * ],
    */
    'allowed_utm_parameters' => [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'utm_campaign_id',
    ],
];
