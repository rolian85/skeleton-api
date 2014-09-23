<?php
namespace Helper;

/** Define the keys used by the CacheService as constants here to avoid keys collision */
class CacheRegistry
{
    const KEY_INTL_LANG = 'lang_';
    const KEY_TIMEZONE_INDEXES_DATA = 'timezone_indexes';
    const KEY_ACCESS_TOKEN = 'access_token_';
    const KEY_VITAL_CONFIG = 'vital_config_';
} 